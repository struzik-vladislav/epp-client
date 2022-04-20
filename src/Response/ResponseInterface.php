<?php

namespace Struzik\EPPClient\Response;

/**
 * Describes the response object.
 */
interface ResponseInterface
{
    /**
     * Associated name for root namespace.
     */
    public const ROOT_NAMESPACE_NAME = 'epp';

    /**
     * Command completed successfully.
     */
    public const RC_SUCCESS = '1000';

    /**
     * Command completed successfully; action pending.
     */
    public const RC_SUCCESS_ACTION_PENDING = '1001';

    /**
     * Command completed successfully; no messages.
     */
    public const RC_SUCCESS_NO_MESSAGES = '1300';

    /**
     * Command completed successfully; ack to dequeue.
     */
    public const RC_SUCCESS_ACK_TO_DEQUEUE = '1301';

    /**
     * Command completed successfully; ending session.
     */
    public const RC_SUCCESS_ENDING_SESSION = '1500';

    /**
     * Unknown command.
     */
    public const RC_UNKNOWN_COMMAND = '2000';

    /**
     * Command syntax error.
     */
    public const RC_SYNTAX_ERROR = '2001';

    /**
     * Command use error.
     */
    public const RC_USE_ERROR = '2002';

    /**
     * Required parameter missing.
     */
    public const RC_PARAMETER_MISSING = '2003';

    /**
     * Parameter value range error.
     */
    public const RC_PARAMETER_VALUE_RANGE_ERROR = '2004';

    /**
     * Parameter value syntax error.
     */
    public const RC_PARAMETER_VALUE_SYNTAX_ERROR = '2005';

    /**
     * Unimplemented protocol version.
     */
    public const RC_UNIMPLEMENTED_PROTOCOL_VERSION = '2100';

    /**
     * Unimplemented command.
     */
    public const RC_UNIMPLEMENTED_COMMAND = '2101';

    /**
     * Unimplemented option.
     */
    public const RC_UNIMPLEMENTED_OPTION = '2102';

    /**
     * Unimplemented extension.
     */
    public const RC_UNIMPLEMENTED_EXTENSION = '2103';

    /**
     * Billing failure.
     */
    public const RC_BILLING_FAILURE = '2104';

    /**
     * Object is not eligible for renewal.
     */
    public const RC_NOT_ELIGIBLE_FOR_RENEWAL = '2105';

    /**
     * Object is not eligible for transfer.
     */
    public const RC_NOT_ELIGIBLE_FOR_TRANSFER = '2106';

    /**
     * Authentication error.
     */
    public const RC_AUTHENTICATION_ERROR = '2200';

    /**
     * Authorization error.
     */
    public const RC_AUTHORIZATION_ERROR = '2201';

    /**
     * Invalid authorization information.
     */
    public const RC_INVALID_AUTHORIZATION_INFORMATION = '2202';

    /**
     * Object pending transfer.
     */
    public const RC_OBJECT_PENDING_TRANSFER = '2300';

    /**
     * Object not pending transfer.
     */
    public const RC_OBJECT_NOT_PENDING_TRANSFER = '2301';

    /**
     * Object exists.
     */
    public const RC_OBJECT_EXISTS = '2302';

    /**
     * Object does not exist.
     */
    public const RC_OBJECT_DOES_NOT_EXIST = '2303';

    /**
     * Object status prohibits operation.
     */
    public const RC_OBJECT_STATUS_PROHIBITS_OPERATION = '2304';

    /**
     * Object association prohibits operation.
     */
    public const RC_OBJECT_ASSOCIATION_PROHIBITS_OPERATION = '2305';

    /**
     * Parameter value policy error.
     */
    public const RC_PARAMETER_VALUE_POLICY_ERROR = '2306';

    /**
     * Unimplemented object service.
     */
    public const RC_UNIMPLEMENTED_OBJECT_SERVICE = '2307';

    /**
     * Data management policy violation.
     */
    public const RC_DATA_MANAGEMENT_POLICY_VIOLATION = '2308';

    /**
     * Command failed.
     */
    public const RC_COMMAND_FAILED = '2400';

    /**
     * Command failed; server closing connection.
     */
    public const RC_COMMAND_FAILED_CONNECTION_CLOSE = '2500';

    /**
     * Authentication error; server closing connection.
     */
    public const RC_AUTHENTICATION_ERROR_CONNECTION_CLOSE = '2501';

    /**
     * Session limit exceeded; server closing connection.
     */
    public const RC_SESSION_LIMIT_EXCEEDED_CONNECTION_CLOSE = '2502';

    /**
     * @param string RAW XML
     */
    public function __construct(string $xml);

    /**
     * Was the request a success.
     */
    public function isSuccess(): bool;

    /**
     * Evaluates the given XPath expression.
     *
     * @param  string The XPath expression to execute
     */
    public function get(string $xpathQuery, \DOMNode $contextNode = null): \DOMNodeList;

    /**
     * Evaluates the given XPath expression and return the first element from DOMNodeList or null.
     */
    public function getFirst(string $xpathQuery, \DOMNode $contextNode = null): ?\DOMNode;

    /**
     * Returns namespaces used in document.
     *
     * @return array Array of namespace names with their associated URIs
     */
    public function getUsedNamespaces(): array;

    /**
     * Adding add-ons in the response object.
     *
     * @param object $addon add-on object
     */
    public function addExtAddon(object $addon): void;

    /**
     * Removing add-on by class name.
     *
     * @param string $classname class name
     */
    public function removeExtAddon(string $classname): void;

    /**
     * Find add-on by class name.
     *
     * @param string $classname class name
     */
    public function findExtAddon(string $classname): ?object;
}
