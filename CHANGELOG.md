# Changelog

All notable changes to `gmponos/http-message-util` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 0.1.0 - 2018-12-19 

### Added
- Created class `HttpMessageUtil\RequestUtil`
- Added function `HttpMessageUtil\RequestUtil::withJsonBody` that allows to alter a request to add a json body and
also adds the `Content-Type` header `application/json`. 
- Added function `HttpMessageUtil\RequestUtil::withQuery` that allows to alter a request URI and set a query string. 
- Added function `HttpMessageUtil\RequestUtil::withQueryOptions` that allows to alter the URI of a request and add query options to it. 
- Added function `HttpMessageUtil\RequestUtil::withHeaders` that accepts an array of headers and passes them to the request. 