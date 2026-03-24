# Kwaishop PHP SDK v1.0.0 Core Design

## Document Status

- Status: Approved for drafting
- Date: 2026-03-24
- Scope: `v1.0.0` core SDK foundation
- Language target: PHP 8.1+

## Context

Kuaishou E-commerce Open Platform currently provides official documentation and a Java SDK, but there is no official PHP SDK for the ecosystem. This project aims to provide a production-grade PHP SDK for broad public use.

The long-term product goal is full API coverage across the open platform. The `v1.0.0` goal is narrower: establish a stable, extensible, officially aligned SDK foundation that can support incremental rollout of resource modules without forcing breaking changes in the transport, signing, authorization, or exception model.

## Product Goal

Deliver a publishable PHP SDK foundation for Kuaishou E-commerce Open Platform that:

- follows the official API protocol and OAuth requirements
- works out of the box with built-in Guzzle transport
- exposes a resource-oriented public API
- supports both authorized and non-authorized API calls
- can scale to broad API coverage without redesigning the core

## Non-Goals for v1.0.0

- full platform API coverage
- complete DTO coverage for all request and response bodies
- automatic code generation from documentation metadata
- framework-specific integrations for Laravel, Hyperf, or Symfony bundles
- persistence adapters for storing tokens in databases or caches
- built-in decryption/encryption support for all future privacy features

## Official Constraints

The SDK design is constrained by the official documentation and notices:

- Production API base URL should default to `https://openapi.kwaixiaodian.com`.
- Legacy production URL `https://open.kwaixiaodian.com` should be treated as a compatibility fallback only.
- OAuth authorize page remains on `https://open.kwaixiaodian.com/oauth/authorize`.
- Token exchange endpoints are `https://openapi.kwaixiaodian.com/oauth2/access_token` and `https://openapi.kwaixiaodian.com/oauth2/refresh_token`.
- API requests use gateway-style public parameters including `appkey`, `method`, `version`, `param`, `access_token`, `timestamp`, `signMethod`, and `sign`.
- Request content type only supports `application/x-www-form-urlencoded`.
- `param` should be placed in the request body for `POST` requests.
- Request signing uses `signSecret`, not `appSecret`.
- `signMethod` supports both `MD5` and `HMAC_SHA256`; `HMAC_SHA256` is the recommended default.
- OAuth2 supports both `authorization_code` and `client_credentials`.
- Return codes include platform-level generic codes and business-domain codes.

## Release Strategy

### Recommended Approach

Use a stable core-first architecture with an extensible resource layer.

This means `v1.0.0` focuses on:

- configuration
- signing
- OAuth token workflows
- HTTP transport
- request assembly
- response parsing
- error mapping
- resource base abstractions

Actual business APIs will be added incrementally in later releases without requiring a redesign of the SDK core.

### Alternatives Considered

#### Option A: Core-first extensible SDK

- Pros: correct long-term architecture, fits full-coverage roadmap, minimizes future breaking changes
- Cons: first release contains less business surface area

#### Option B: Fully hand-written business modules first

- Pros: faster visible business APIs
- Cons: high maintenance cost, repeated protocol code, greater inconsistency risk

#### Option C: Raw request client first

- Pros: fastest to ship
- Cons: poor developer ergonomics, weak public API shape, inconsistent with the resource-style requirement

### Decision

Choose Option A.

## Public API Design

### Primary Entry Point

The SDK will expose a primary client:

```php
$client = new KwaiShopClient($config);
```

### Resource Style

The public API should be resource-oriented:

```php
$client->orders()->list($params, $accessToken);
$client->items()->detail($params, $accessToken);
```

This shape is preferred over a raw method-only gateway because it is easier to discover, document, and maintain for public use.

### Escape Hatch

The SDK should also expose a low-level request method for newly released APIs not yet wrapped by a resource:

```php
$client->rawRequest('open.seller.order.list', $params, $accessToken);
```

This method is not the primary interface, but it is necessary to keep the SDK useful while the wrapper surface catches up with platform growth.

## Return Value Strategy

### Decision

Return arrays by default in `v1.0.0`.

### Reasoning

The platform surface is large, frequently expanding, and uneven across domains. Full DTO modeling in `v1.0.0` would slow delivery and make broad coverage harder. For the first stable foundation release, correctness of transport, auth, signing, and exception handling is more important than exhaustive typed object mapping.

### Rule

- OAuth token responses may use dedicated lightweight value objects.
- General API calls should return normalized arrays.
- DTOs can be introduced later for high-value domains without breaking the raw array contract if added as optional layers.

## Configuration Model

Create a `Config` value object with at least:

- `appKey`
- `appSecret`
- `signSecret`
- `baseUrl`
- `oauthAuthorizeUrl`
- `oauthAccessTokenUrl`
- `oauthRefreshTokenUrl`
- `signMethod`
- `connectTimeout`
- `readTimeout`
- `retryTimes`
- `userAgent`

### Defaults

- `baseUrl`: `https://openapi.kwaixiaodian.com`
- `oauthAuthorizeUrl`: `https://open.kwaixiaodian.com/oauth/authorize`
- `oauthAccessTokenUrl`: `https://openapi.kwaixiaodian.com/oauth2/access_token`
- `oauthRefreshTokenUrl`: `https://openapi.kwaixiaodian.com/oauth2/refresh_token`
- `signMethod`: `HMAC_SHA256`

## Architecture

### Major Components

#### `KwaiShopClient`

- user-facing SDK entry point
- owns shared configuration and transport dependencies
- exposes resource accessors
- exposes `rawRequest()`

#### `OAuthClient`

- builds authorize URLs
- exchanges authorization `code` for tokens
- refreshes access tokens
- retrieves app-level tokens via `client_credentials`

#### `TransportInterface`

- abstracts the HTTP layer
- allows future replacement if needed
- `v1.0.0` implementation uses Guzzle

#### `GuzzleTransport`

- sends form-urlencoded requests
- applies timeouts and retry behavior
- captures HTTP-level failures

#### `SignerInterface`

- signs gateway parameters using the configured sign method
- normalizes the signature entry point for both MD5 and HMAC_SHA256

#### `Md5Signer`

- compatibility implementation

#### `HmacSha256Signer`

- recommended default implementation

#### `RequestFactory`

- assembles public parameters
- serializes `param`
- injects timestamps and auth data
- computes signatures

#### `ResponseParser`

- decodes platform responses
- normalizes data payloads
- maps official return codes to exceptions

#### `AbstractResource`

- base class for all resource modules
- centralizes shared request helpers

#### Resource Modules

Initially only skeleton resource classes are needed, for example:

- `ItemsResource`
- `OrdersResource`
- `AfterSalesResource`
- `LogisticsResource`
- `ShopResource`

These may ship with limited method counts in early versions, but their interface boundaries should be established in `v1.0.0`.

## Request Lifecycle

### Authorized API Call

1. User code calls a resource method or `rawRequest()`.
2. The client resolves the open-platform method name and API version.
3. The request factory serializes business params into `param`.
4. Public parameters are assembled: `appkey`, `method`, `version`, `param`, `timestamp`, `signMethod`, `access_token`.
5. The signer computes `sign` using `signSecret`.
6. The transport sends a `POST` request using `application/x-www-form-urlencoded`.
7. The response parser decodes payloads and checks result codes.
8. The SDK returns a normalized array or throws a typed exception.

### OAuth Token Flow

1. `OAuthClient` builds the authorize URL.
2. The platform redirects back with a `code`.
3. SDK exchanges the `code` using `appKey` and `appSecret`.
4. SDK returns token data, including access token and refresh token.
5. When the access token expires, refresh uses the documented refresh endpoint.

### Non-Authorized API Call

For APIs that do not require user authorization:

1. `OAuthClient` retrieves an app-scoped token using `client_credentials`.
2. That token is supplied to the same gateway request pipeline.

## Signing Rules

### Decisions

- Use `signSecret` as the signing secret source.
- Default `signMethod` to `HMAC_SHA256`.
- Keep MD5 available for explicit compatibility use.

### Guardrails

The SDK must protect users from common official failure modes:

- using `appSecret` instead of `signSecret`
- encoding `param` before signing
- incorrect parameter sort order
- missing required public parameters
- inconsistent timestamp formatting

The request factory and signer should own these rules centrally so resource methods never need to reimplement them.

## Authorization Model

The SDK must support both official OAuth modes:

- `authorization_code` for APIs marked as requiring user authorization
- `client_credentials` for APIs without user authorization requirements

### Token Storage Decision

Do not include persistent token storage in `v1.0.0`.

Instead:

- return structured token results
- let consumers decide how to persist and refresh tokens
- provide helper methods that make refresh workflows simple to implement

This keeps the SDK framework-agnostic and avoids forcing storage semantics on users.

## Error Handling

### Exception Taxonomy

At minimum, define:

- `KwaiShopException`
- `TransportException`
- `AuthenticationException`
- `AuthorizationException`
- `SignatureException`
- `ValidationException`
- `RateLimitException`
- `BusinessException`

### Return Code Mapping

The parser should explicitly recognize and map important official codes:

- `21`: auth failure
- `22`: no access permission
- `24`: token check failure
- `27`: sign check failure
- `28`: token/sign/scope failure
- `1016`: QPS exceeded
- `1017`: daily quota exceeded
- `804000/80400x`: malformed request parameter errors
- `802000/80200x`: platform throttling and gateway protection errors

### Retry Policy

Retries should be conservative and configurable.

Retry candidates:

- transient network failures
- gateway timeout or internal concurrency failure
- selected `802000`, `803000`, `805000`, `806000` class failures when they are clearly transient

Do not retry:

- signature failures
- validation failures
- authorization failures
- quota exceeded errors

## Security and Compliance Extension Points

Official notices show that privacy protection and encrypted data handling are evolving areas for the platform. `v1.0.0` should therefore reserve clean extension points for:

- encrypted field handling
- privacy data decryption adapters
- electronic waybill integrations
- secure callback validation

These are not required for the first core release, but the architecture should not block them.

## Package Structure

Suggested initial layout:

```text
src/
  KwaiShopClient.php
  Config/
    Config.php
  OAuth/
    OAuthClient.php
    TokenResponse.php
  Http/
    TransportInterface.php
    GuzzleTransport.php
  Sign/
    SignerInterface.php
    Md5Signer.php
    HmacSha256Signer.php
  Request/
    RequestFactory.php
  Response/
    ResponseParser.php
  Resource/
    AbstractResource.php
    ItemsResource.php
    OrdersResource.php
    AfterSalesResource.php
    LogisticsResource.php
    ShopResource.php
  Exception/
    KwaiShopException.php
    TransportException.php
    AuthenticationException.php
    AuthorizationException.php
    SignatureException.php
    ValidationException.php
    RateLimitException.php
    BusinessException.php
  Support/
    Arr.php
    Json.php
    Clock.php
tests/
examples/
docs/
```

## Dependency Decisions

### HTTP Client

Use built-in Guzzle for `v1.0.0`.

Reason:

- faster onboarding for users
- straightforward operational behavior
- smaller public abstraction surface in the first release

### Standards

The internal structure should still be clean enough that a future PSR transport adapter can be added without breaking the public API.

## Versioning and Compatibility

### Semantic Versioning

Use semantic versioning from the first public release.

### Compatibility Promise for v1.x

The following should remain stable through `v1.x`:

- client construction shape
- resource accessor pattern
- OAuth method signatures
- low-level `rawRequest()` contract
- exception hierarchy roots

The following may expand without breaking changes:

- new resources
- new resource methods
- additional exception metadata
- helper utilities

## Testing Strategy

### Required Test Layers for v1.0.0

#### Unit Tests

- signature generation for MD5 and HMAC_SHA256
- parameter sorting
- request assembly
- OAuth request building
- response parsing and code mapping

#### Transport Tests

- Guzzle request construction
- `application/x-www-form-urlencoded` encoding
- `param` body placement for POST
- timeout and retry behavior

#### Contract Tests

- official sample payload compatibility where available
- return code mapping coverage for common platform errors

#### Integration-Style Tests

- mock OAuth token exchange
- mock authorized API request
- mock rate-limit and signature-failure scenarios

### Minimum Test Coverage Objective

Every protocol-critical component must have direct tests:

- signer
- request factory
- OAuth client
- response parser
- transport retry rules

## Documentation Strategy

`v1.0.0` should ship with:

- installation instructions
- quick start
- OAuth examples
- raw request example
- resource example
- error handling guide
- token refresh example

Examples should use real official concepts and parameter names, not invented abstractions.

## Milestones

### Milestone 1

Core infrastructure:

- config
- signer
- transport
- request factory
- response parser
- exception hierarchy

### Milestone 2

OAuth support:

- authorize URL builder
- code token exchange
- refresh token flow
- client credentials flow

### Milestone 3

Public client and resource scaffolding:

- `KwaiShopClient`
- base resources
- `rawRequest()`
- initial resource shells

### Milestone 4

Quality and docs:

- tests
- examples
- README expansion
- release preparation

## Open Questions Deferred

The following are intentionally deferred beyond this core design:

- whether to add generated API wrappers
- whether to persist tokens via interfaces in `v1.1+`
- whether to add PSR transport support
- whether to provide Laravel integration
- how to package privacy-data decryption helpers

## Final Decision Summary

`v1.0.0` will be a stable, publishable SDK foundation with:

- PHP 8.1+ support
- built-in Guzzle transport
- resource-oriented public API
- array-first general responses
- OAuth2 support for both `authorization_code` and `client_credentials`
- `HMAC_SHA256` as default signing method
- `signSecret`-based signing
- `https://openapi.kwaixiaodian.com` as the default production API base URL
- explicit room for later full API expansion

## References

- Official developer guide: `https://open.kwaixiaodian.com/zone/new/docs/dev`
- API calling guide: `https://open.kwaixiaodian.com/zone/new/docs/dev?pageSign=8cca5d25ba0015e5045a7ebec6383b741614263875756`
- OAuth guide: `https://open.kwaixiaodian.com/zone/new/docs/dev?pageSign=e1d9e229332f4f233a04b44833a5dfe71614263940720`
- App development guide: `https://open.kwaixiaodian.com/zone/new/docs/dev?pageSign=4852036ba63c13df7f5fd2ddc38169581614263613785`
- SDK usage guide: `https://open.kwaixiaodian.com/zone/new/docs/dev?pageSign=2cb0d14623549774e8c8e06994bbcadb1614263973792`
- Return code guide: `https://open.kwaixiaodian.com/zone/new/docs/dev?pageSign=5de448fecaddd4c58104e8aea442695b1614263998209`
- Endpoint migration notice: `https://open.kwaixiaodian.com/zone/new/announcement/detail?cateId=2&pageSign=98e4891013e862ea44ea7ced8eaf54a01657508418600`
- Signature algorithm notice: `https://open.kwaixiaodian.com/zone/new/announcement/detail?cateId=2&pageSign=020ddb9280ebe8e67ceafce66555b63d1643105860867`
