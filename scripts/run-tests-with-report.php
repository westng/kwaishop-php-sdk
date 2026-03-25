<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$phpBinary = PHP_BINARY;
$phpunit = $projectRoot . '/vendor/phpunit/phpunit/phpunit';
$reportPath = $projectRoot . '/test-report.html';
$artifactsDirectory = $projectRoot . '/.test-artifacts';

resetArtifactsDirectory($artifactsDirectory);

$suites = [
    [
        'key' => 'unit',
        'label' => '单元测试',
        'command' => [$phpBinary, $phpunit, '--configuration', $projectRoot . '/phpunit.xml', '--testsuite', 'unit'],
        'description' => 'PHPUnit 单元测试',
    ],
    [
        'key' => 'integration',
        'label' => '集成测试',
        'command' => [$phpBinary, $phpunit, '--configuration', $projectRoot . '/phpunit.xml', '--testsuite', 'integration'],
        'description' => 'PHPUnit 集成测试',
    ],
    [
        'key' => 'functional',
        'label' => '功能测试',
        'command' => [$phpBinary, $phpunit, '--configuration', $projectRoot . '/phpunit.xml', '--testsuite', 'functional'],
        'description' => 'PHPUnit 功能测试套件',
    ],
];

$suiteResults = [];
$overallExitCode = 0;

foreach ($suites as $suite) {
    $result = runCommand($suite['command'], $projectRoot);
    $suiteResults[] = [
        'key' => $suite['key'],
        'label' => $suite['label'],
        'description' => $suite['description'],
        'command' => implode(' ', array_map(escapeShellToken(...), $suite['command'])),
        'exitCode' => $result['exitCode'],
        'output' => $result['output'],
        'metrics' => extractMetrics($result['output']),
        'status' => determineSuiteStatus($result['exitCode'], $result['output']),
    ];

    if ($result['exitCode'] !== 0) {
        $overallExitCode = $result['exitCode'];
    }
}

renderConsoleSummary($suiteResults);

$html = renderReport(
    generatedAt: date('Y-m-d H:i:s'),
    phpVersion: PHP_VERSION,
    phpunitVersion: detectPhpUnitVersion($suiteResults),
    overallStatus: determineOverallStatus($suiteResults, $overallExitCode),
    overallExitCode: $overallExitCode,
    suiteResults: $suiteResults,
    artifacts: collectArtifacts($artifactsDirectory)
);

file_put_contents($reportPath, $html);

exit($overallExitCode);

/**
 * @param list<string> $command
 *
 * @return array{exitCode:int, output:string}
 */
function runCommand(array $command, string $cwd): array
{
    $descriptorSpec = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $env = [];

    foreach (array_merge($_ENV, $_SERVER) as $key => $value) {
        if (!is_string($key)) {
            continue;
        }

        if (!is_scalar($value) && $value !== null) {
            continue;
        }

        $env[$key] = $value === null ? '' : (string) $value;
    }

    $env['KWAISHOP_TEST_ARTIFACTS_DIR'] = dirname(__DIR__) . '/.test-artifacts';

    $process = proc_open($command, $descriptorSpec, $pipes, $cwd, $env);

    if (!is_resource($process)) {
        fwrite(STDERR, 'Failed to start test process.' . PHP_EOL);
        exit(1);
    }

    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    fclose($pipes[2]);

    $exitCode = proc_close($process);
    $output = trim((string) $stdout . (($stderr !== '' && $stderr !== false) ? PHP_EOL . $stderr : ''));

    return [
        'exitCode' => $exitCode,
        'output' => $output === '' ? 'No output captured.' : $output,
    ];
}

function resetArtifactsDirectory(string $directory): void
{
    if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
        fwrite(STDERR, 'Failed to prepare test artifacts directory.' . PHP_EOL);
        exit(1);
    }

    foreach (glob($directory . '/*.json') ?: [] as $file) {
        @unlink($file);
    }
}

/**
 * @return array{tests:int|null, assertions:int|null, skipped:int|null, failures:int|null, errors:int|null}
 */
function extractMetrics(string $output): array
{
    $tests = extractMetric($output, 'Tests');
    $assertions = extractMetric($output, 'Assertions');
    $skipped = extractMetric($output, 'Skipped');
    $failures = extractMetric($output, 'Failures');
    $errors = extractMetric($output, 'Errors');

    if (($tests === null || $assertions === null)
        && preg_match('/OK \((\d+) tests?, (\d+) assertions?\)/', $output, $matches) === 1) {
        $tests ??= (int) $matches[1];
        $assertions ??= (int) $matches[2];
    }

    return [
        'tests' => $tests,
        'assertions' => $assertions,
        'skipped' => $skipped,
        'failures' => $failures,
        'errors' => $errors,
    ];
}

function extractMetric(string $output, string $label): ?int
{
    if (!preg_match('/' . preg_quote($label, '/') . ':\s+(\d+)/', $output, $matches)) {
        return null;
    }

    return (int) $matches[1];
}

function determineSuiteStatus(int $exitCode, string $output): string
{
    if ($exitCode !== 0) {
        return 'FAILED';
    }

    if (str_contains($output, 'No tests executed!')) {
        return 'EMPTY';
    }

    if (preg_match('/Skipped:\s+([1-9]\d*)/', $output) === 1) {
        return 'SKIPPED';
    }

    return 'PASSED';
}

/**
 * @param list<array<string, mixed>> $suiteResults
 */
function determineOverallStatus(array $suiteResults, int $overallExitCode): string
{
    if ($overallExitCode !== 0) {
        return 'FAILED';
    }

    foreach ($suiteResults as $suiteResult) {
        if (($suiteResult['status'] ?? '') === 'SKIPPED') {
            return 'PASSED WITH SKIPS';
        }
    }

    foreach ($suiteResults as $suiteResult) {
        if (($suiteResult['status'] ?? '') === 'EMPTY') {
            return 'PASSED WITH EMPTY SUITES';
        }
    }

    return 'PASSED';
}

/**
 * @param list<array<string, mixed>> $suiteResults
 */
function detectPhpUnitVersion(array $suiteResults): string
{
    foreach ($suiteResults as $suiteResult) {
        if (preg_match('/PHPUnit\s+([0-9.]+)/', (string) $suiteResult['output'], $matches) === 1) {
            return $matches[1];
        }
    }

    return 'unknown';
}

/**
 * @param list<array<string, mixed>> $suiteResults
 */
function renderConsoleSummary(array $suiteResults): void
{
    foreach ($suiteResults as $suiteResult) {
        fwrite(STDOUT, "=== {$suiteResult['label']} ===" . PHP_EOL);
        fwrite(STDOUT, (string) $suiteResult['output'] . PHP_EOL . PHP_EOL);
    }
}

function escapeShellToken(string $token): string
{
    if (preg_match('/^[a-zA-Z0-9_:\\/.=-]+$/', $token) === 1) {
        return $token;
    }

    return "'" . str_replace("'", "'\\''", $token) . "'";
}

/**
 * @param list<array{
 *     key:string,
 *     label:string,
 *     description:string,
 *     command:string,
 *     exitCode:int,
 *     output:string,
 *     metrics:array{tests:int|null, assertions:int|null, skipped:int|null, failures:int|null, errors:int|null},
 *     status:string
 * }> $suiteResults
 * @param list<array{name:string, content:string}> $artifacts
 */
function renderReport(
    string $generatedAt,
    string $phpVersion,
    string $phpunitVersion,
    string $overallStatus,
    int $overallExitCode,
    array $suiteResults,
    array $artifacts
): string {
    $generatedAt = htmlspecialchars($generatedAt, ENT_QUOTES, 'UTF-8');
    $phpVersion = htmlspecialchars($phpVersion, ENT_QUOTES, 'UTF-8');
    $phpunitVersion = htmlspecialchars($phpunitVersion, ENT_QUOTES, 'UTF-8');
    $overallStatus = htmlspecialchars($overallStatus, ENT_QUOTES, 'UTF-8');
    $localizedOverallStatus = htmlspecialchars(localizeStatus(htmlspecialchars_decode($overallStatus, ENT_QUOTES)), ENT_QUOTES, 'UTF-8');

    $statusColor = match ($overallStatus) {
        'FAILED' => '#b42318',
        'PASSED WITH SKIPS', 'PASSED WITH EMPTY SUITES' => '#b54708',
        default => '#1f7a3d',
    };

    $statusBackground = match ($overallStatus) {
        'FAILED' => '#fef3f2',
        'PASSED WITH SKIPS', 'PASSED WITH EMPTY SUITES' => '#fffaeb',
        default => '#ecfdf3',
    };

    $overviewCards = '';
    foreach ($suiteResults as $suiteResult) {
        $overviewCards .= sprintf(
            '<div class="suite-card">
                <div class="suite-label">%s</div>
                <div class="suite-status suite-status-%s">%s</div>
                <div class="suite-meta">用例数：%s</div>
                <div class="suite-meta">断言数：%s</div>
                <div class="suite-meta">跳过数：%s</div>
            </div>',
            htmlspecialchars($suiteResult['label'], ENT_QUOTES, 'UTF-8'),
            strtolower(str_replace(' ', '-', $suiteResult['status'])),
            htmlspecialchars(localizeStatus($suiteResult['status']), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) ($suiteResult['metrics']['tests'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) ($suiteResult['metrics']['assertions'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) ($suiteResult['metrics']['skipped'] ?? '-'), ENT_QUOTES, 'UTF-8')
        );
    }

    $suiteSections = '';
    foreach ($suiteResults as $suiteResult) {
        $suiteSections .= sprintf(
            '<section class="suite-section">
                <h2>%s</h2>
                <div class="section-meta">%s</div>
                <div class="section-meta">执行命令：<code>%s</code></div>
                <div class="metric-row">
                    <span>退出码：%s</span>
                    <span>用例数：%s</span>
                    <span>断言数：%s</span>
                    <span>跳过数：%s</span>
                    <span>失败数：%s</span>
                    <span>错误数：%s</span>
                </div>
                <pre>%s</pre>
            </section>',
            htmlspecialchars($suiteResult['label'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($suiteResult['description'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($suiteResult['command'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) $suiteResult['exitCode'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) ($suiteResult['metrics']['tests'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) ($suiteResult['metrics']['assertions'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) ($suiteResult['metrics']['skipped'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) ($suiteResult['metrics']['failures'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars((string) ($suiteResult['metrics']['errors'] ?? '-'), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($suiteResult['output'], ENT_QUOTES, 'UTF-8')
        );
    }

    $artifactSections = '<section class="suite-section"><h2>接口返回数据</h2><div class="section-meta">本次未采集到接口返回数据。</div></section>';

    if ($artifacts !== []) {
        $artifactSections = '';

        foreach ($artifacts as $artifact) {
            $decoded = json_decode($artifact['content'], true);
            $displayContent = $artifact['content'];

            if (is_array($decoded) && ($decoded['contentType'] ?? null) === 'raw' && isset($decoded['body']) && is_string($decoded['body'])) {
                $displayContent = $decoded['body'];
            }

            $artifactSections .= sprintf(
                '<section class="suite-section">
                    <h2>接口返回数据</h2>
                    <div class="section-meta">接口标识：%s</div>
                    <pre>%s</pre>
                </section>',
                htmlspecialchars($artifact['name'], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($displayContent, ENT_QUOTES, 'UTF-8')
            );
        }
    }

    return <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KwaiShopSDK 测试报告</title>
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --text: #0f172a;
            --muted: #667085;
            --border: #d0d5dd;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 32px;
            background: radial-gradient(circle at top, #e9f2ff 0%, var(--bg) 42%, #f8fafc 100%);
            color: var(--text);
            font: 14px/1.6 "SFMono-Regular", Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 28px;
        }
        h1 {
            margin: 0 0 10px;
            font-size: 30px;
            line-height: 1.2;
        }
        .meta {
            color: var(--muted);
        }
        .status {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid {$statusColor};
            color: {$statusColor};
            background: {$statusBackground};
            font-weight: 700;
            white-space: nowrap;
        }
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }
        .suite-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: #fbfcfe;
            padding: 16px;
        }
        .suite-label {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .suite-status {
            display: inline-block;
            margin-bottom: 10px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
        .suite-status-passed { background: #ecfdf3; color: #1f7a3d; }
        .suite-status-skipped { background: #fffaeb; color: #b54708; }
        .suite-status-empty { background: #eef2ff; color: #4338ca; }
        .suite-status-failed { background: #fef3f2; color: #b42318; }
        .suite-meta {
            color: var(--muted);
        }
        .suite-section + .suite-section {
            margin-top: 26px;
        }
        .suite-section h2 {
            margin: 0 0 8px;
            font-size: 18px;
        }
        .section-meta {
            color: var(--muted);
            margin-bottom: 6px;
        }
        .metric-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 12px 0;
            color: var(--text);
        }
        code {
            padding: 2px 6px;
            border-radius: 8px;
            background: #f2f4f7;
        }
        pre {
            margin: 0;
            padding: 18px;
            overflow: auto;
            border-radius: 16px;
            border: 1px solid #1e293b;
            background: #0f172a;
            color: #e2e8f0;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .footer {
            margin-top: 22px;
            color: var(--muted);
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="panel">
            <div class="header">
                <div>
                    <h1>KwaiShopSDK 测试报告</h1>
                    <div class="meta">生成时间：{$generatedAt}</div>
                    <div class="meta">PHP {$phpVersion} · PHPUnit {$phpunitVersion} · 退出码 {$overallExitCode}</div>
                </div>
                <div class="status">{$localizedOverallStatus}</div>
            </div>

            <div class="overview-grid">
                {$overviewCards}
            </div>

            {$suiteSections}

            {$artifactSections}

            <div class="footer">该报告由 composer test 自动生成。报告文件：test-report.html</div>
        </div>
    </div>
</body>
</html>
HTML;
}

function localizeStatus(string $status): string
{
    return match ($status) {
        'PASSED' => '通过',
        'FAILED' => '失败',
        'SKIPPED' => '通过（含跳过）',
        'EMPTY' => '空测试集',
        'PASSED WITH SKIPS' => '通过（含跳过）',
        'PASSED WITH EMPTY SUITES' => '通过（含空测试集）',
        default => $status,
    };
}

/**
 * @return list<array{name:string, content:string}>
 */
function collectArtifacts(string $directory): array
{
    $artifacts = [];

    foreach (glob($directory . '/*.json') ?: [] as $file) {
        $content = file_get_contents($file);
        if ($content === false) {
            continue;
        }

        $artifacts[] = [
            'name' => basename($file, '.json'),
            'content' => $content,
        ];
    }

    return $artifacts;
}
