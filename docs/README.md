# Overview

![Build Status](https://github.com/struzik-vladislav/epp-client/actions/workflows/ci.yml/badge.svg?branch=master)
[![Latest Stable Version](https://img.shields.io/github/v/release/struzik-vladislav/epp-client?sort=semver&style=flat-square)](https://packagist.org/packages/struzik-vladislav/epp-client)
[![Total Downloads](https://img.shields.io/packagist/dt/struzik-vladislav/epp-client?style=flat-square)](https://packagist.org/packages/struzik-vladislav/epp-client/stats)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![StandWithUkraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

The Extensible Provisioning Protocol (EPP) is a protocol used to manage domain names and other Internet resources. It uses XML messages to communicate between clients and servers.  It facilitates communication between clients (like software applications or domain registrars) and servers responsible for managing these resources.

## Standards Implemented
**Extensible Provisioning Protocol (EPP):** The library adheres to the core EPP protocol defined in [RFC 5730](https://tools.ietf.org/html/rfc5730). This ensures compatibility with standard EPP servers.

**Additional RFCs:** It also implements functionalities specified in several other RFCs relevant to domain names and related resources:
* [RFC 3731](https://tools.ietf.org/html/rfc3731): Extensible Provisioning Protocol (EPP) Domain Name Mapping
* [RFC 5732](https://tools.ietf.org/html/rfc5732): Extensible Provisioning Protocol (EPP) Host Mapping
* [RFC 5733](https://tools.ietf.org/html/rfc5733): Extensible Provisioning Protocol (EPP) Contact Mapping
* [RFC 5734](https://tools.ietf.org/html/rfc5734): Extensible Provisioning Protocol (EPP) Transport over TCP
* [RFC 3735](https://tools.ietf.org/html/rfc3735): Guidelines for Extending the Extensible Provisioning Protocol (EPP)

## Structure and Key Components
**Client Class:** The EPPClient class serves as the main entry point for interacting with the EPP server. You configure your connection and send/receive commands through this class.

**Connection Handling:** The library provides flexibility in connecting to EPP servers through various connection implementations. Popular options include StreamSocketConnection for basic TCP connections and RabbitMQConnection for connections via RabbitMQ.
* [struzik-vladislav/epp-socket-connection](https://github.com/struzik-vladislav/epp-socket-connection) - Socket connection to the EPP servers
* [struzik-vladislav/epp-rabbitmq-connection](https://github.com/struzik-vladislav/epp-rabbitmq-connection) - Connection to the EPP servers via RabbitMQ

**Command Classes:** Specific EPP commands are represented by individual classes. Each class encapsulates logic for generating and processing XML messages according to the corresponding RFC specifications. Examples include DomainInfoRequest, DomainCreateRequest, etc.
* [Session commands](session-commands.md)
* [Contact commands](contact-commands.md) 
* [Domain commands](domain-commands.md)
* [Host commands](host-commands.md)
* [Poll  commands](poll-commands.md)

**Extensions:** The library supports various extensions for specialized functionalities beyond the core EPP protocol. The list of available extensions is updated as new extensions are implemented. The actual list of extensions:
* [struzik-vladislav/epp-ext-rgp](https://github.com/struzik-vladislav/epp-ext-rgp) - Domain Registry Grace Period (RGP) extension for the EPP Client
* [struzik-vladislav/epp-ext-secdns](https://github.com/struzik-vladislav/epp-ext-secdns) - DNS Security Extension for the EPP Client
* [struzik-vladislav/epp-ext-hostmasterua-uaepp](https://github.com/struzik-vladislav/epp-ext-hostmasterua-uaepp) - UAEPP extension provided by [HostmasterUA](https://hostmaster.ua/)
* [struzik-vladislav/epp-ext-hostmasterua-balance](https://github.com/struzik-vladislav/epp-ext-hostmasterua-balance) - Balance extension provided by [HostmasterUA](https://hostmaster.ua/)
* [struzik-vladislav/epp-ext-iddigital-charge](https://github.com/struzik-vladislav/epp-ext-iddigital-charge) - Charge extension provided by [Identity Digital](https://www.identity.digital/)
* [struzik-vladislav/epp-ext-iddigital-kv](https://github.com/struzik-vladislav/epp-ext-iddigital-kv) - Key-Value extension provided by [Identity Digital](https://www.identity.digital/)

**Tools:** Add-ons that allow you to integrate various services or developer tools with the EPP client.
* [struzik-vladislav/epp-monolog-formatter](https://github.com/struzik-vladislav/epp-monolog-formatter) - Requests/Responses [monolog/monolog](https://github.com/Seldaek/monolog) formatter for hiding sensitive data  
