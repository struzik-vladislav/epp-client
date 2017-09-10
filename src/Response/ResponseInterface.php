<?php

namespace Struzik\EPPClient\Response;

/**
 * Describes the response object.
 */
interface ResponseInterface
{
    /**
     * Associated name for root namespace.
     *
     * @const ROOT_NAMESPACE_NAME
     */
    const ROOT_NAMESPACE_NAME = 'epp';

    /**
     * Command completed successfully.
     *
     * @const RC_SUCCESS
     */
    const RC_SUCCESS = '1000';

    /**
     * Command completed successfully; action pending.
     *
     * @const RC_SUCCESS_ACTION_PENDING
     */
    const RC_SUCCESS_ACTION_PENDING = '1001';

    /**
     * Command completed successfully; no messages.
     *
     * @const RC_SUCCESS_NO_MESSAGES
     */
    const RC_SUCCESS_NO_MESSAGES = '1300';

    /**
     * Command completed successfully; ack to dequeue.
     *
     * @const RC_SUCCESS_ACK_TO_DEQUEUE
     */
    const RC_SUCCESS_ACK_TO_DEQUEUE = '1301';

    /**
     * Command completed successfully; ending session.
     *
     * @const RC_SUCCESS_ENDING_SESSION
     */
    const RC_SUCCESS_ENDING_SESSION = '1500';

    /**
     * Unknown command.
     *
     * @const RC_UNKNOWN_COMMAND
     */
    const RC_UNKNOWN_COMMAND = '2000';

    /**
     * Command syntax error.
     *
     * @const RC_SYNTAX_ERROR
     */
    const RC_SYNTAX_ERROR = '2001';

    /**
     * Command use error.
     *
     * @const RC_USE_ERROR
     */
    const RC_USE_ERROR = '2002';

    /**
     * Required parameter missing.
     *
     * @const RC_PARAMETER_MISSING
     */
    const RC_PARAMETER_MISSING = '2003';

    /**
     * Parameter value range error.
     *
     * @const RC_PARAMETER_VALUE_RANGE_ERROR
     */
    const RC_PARAMETER_VALUE_RANGE_ERROR = '2004';

    /**
     * Parameter value syntax error.
     *
     * @const RC_PARAMETER_VALUE_SYNTAX_ERROR
     */
    const RC_PARAMETER_VALUE_SYNTAX_ERROR = '2005';

    /**
     * Unimplemented protocol version.
     *
     * @const RC_UNIMPLEMENTED_PROTOCOL_VERSION
     */
    const RC_UNIMPLEMENTED_PROTOCOL_VERSION = '2100';

    /**
     * Unimplemented command.
     *
     * @const RC_UNIMPLEMENTED_COMMAND
     */
    const RC_UNIMPLEMENTED_COMMAND = '2101';

    /**
     * Unimplemented option.
     *
     * @const RC_UNIMPLEMENTED_OPTION
     */
    const RC_UNIMPLEMENTED_OPTION = '2102';

    /**
     * Unimplemented extension.
     *
     * @const RC_UNIMPLEMENTED_EXTENSION
     */
    const RC_UNIMPLEMENTED_EXTENSION = '2103';

    /**
     * Billing failure.
     *
     * @const RC_BILLING_FAILURE
     */
    const RC_BILLING_FAILURE = '2104';

    /**
     * Object is not eligible for renewal.
     *
     * @const RC_NOT_ELIGIBLE_FOR_RENEWAL
     */
    const RC_NOT_ELIGIBLE_FOR_RENEWAL = '2105';

    /**
     * Object is not eligible for transfer.
     *
     * @const RC_NOT_ELIGIBLE_FOR_TRANSFER
     */
    const RC_NOT_ELIGIBLE_FOR_TRANSFER = '2106';

    /**
     * Authentication error.
     *
     * @const RC_AUTHENTICATION_ERROR
     */
    const RC_AUTHENTICATION_ERROR = '2200';

    /**
     * Authorization error.
     *
     * @const RC_AUTHORIZATION_ERROR
     */
    const RC_AUTHORIZATION_ERROR = '2201';

    /**
     * Invalid authorization information.
     *
     * @const RC_INVALID_AUTHORIZATION_INFORMATION
     */
    const RC_INVALID_AUTHORIZATION_INFORMATION = '2202';

    /**
     * Object pending transfer.
     *
     * @const RC_OBJECT_PENDING_TRANSFER
     */
    const RC_OBJECT_PENDING_TRANSFER = '2300';

    /**
     * Object not pending transfer.
     *
     * @const RC_OBJECT_NOT_PENDING_TRANSFER
     */
    const RC_OBJECT_NOT_PENDING_TRANSFER = '2301';

    /**
     * Object exists.
     *
     * @const RC_OBJECT_EXISTS
     */
    const RC_OBJECT_EXISTS = '2302';

    /**
     * Object does not exist.
     *
     * @const RC_OBJECT_DOES_NOT_EXIST
     */
    const RC_OBJECT_DOES_NOT_EXIST = '2303';

    /**
     * Object status prohibits operation.
     *
     * @const RC_OBJECT_STATUS_PROHIBITS_OPERATION
     */
    const RC_OBJECT_STATUS_PROHIBITS_OPERATION = '2304';

    /**
     * Object association prohibits operation.
     *
     * @const RC_OBJECT_ASSOCIATION_PROHIBITS_OPERATION
     */
    const RC_OBJECT_ASSOCIATION_PROHIBITS_OPERATION = '2305';

    /**
     * Parameter value policy error.
     *
     * @const RC_PARAMETER_VALUE_POLICY_ERROR
     */
    const RC_PARAMETER_VALUE_POLICY_ERROR = '2306';

    /**
     * Unimplemented object service.
     *
     * @const RC_UNIMPLEMENTED_OBJECT_SERVICE
     */
    const RC_UNIMPLEMENTED_OBJECT_SERVICE = '2307';

    /**
     * Data management policy violation.
     *
     * @const RC_DATA_MANAGEMENT_POLICY_VIOLATION
     */
    const RC_DATA_MANAGEMENT_POLICY_VIOLATION = '2308';

    /**
     * Command failed.
     *
     * @const RC_COMMAND_FAILED
     */
    const RC_COMMAND_FAILED = '2400';

    /**
     * Command failed; server closing connection.
     *
     * @const RC_COMMAND_FAILED_CONNECTION_CLOSE
     */
    const RC_COMMAND_FAILED_CONNECTION_CLOSE = '2500';

    /**
     * Authentication error; server closing connection.
     *
     * @const RC_AUTHENTICATION_ERROR_CONNECTION_CLOSE
     */
    const RC_AUTHENTICATION_ERROR_CONNECTION_CLOSE = '2501';

    /**
     * Session limit exceeded; server closing connection.
     *
     * @const RC_SESSION_LIMIT_EXCEEDED_CONNECTION_CLOSE
     */
    const RC_SESSION_LIMIT_EXCEEDED_CONNECTION_CLOSE = '2502';

    /**
     * @param string RAW XML
     */
    public function __construct($xml);

    /**
     * Was the request a success.
     *
     * @return bool
     */
    public function isSuccess();

    /**
     * Evaluates the given XPath expression.
     *
     * @param  string The XPath expression to execute
     *
     * @return \DOMNodeList
     */
    public function get($xpathQuery, \DOMNode $contextnode = null);

    /**
     * Evaluates the given XPath expression and return the first element from DOMNodeList or null.
     *
     * @param  string
     *
     * @return \DOMNode|null
     */
    public function getFirst($xpathQuery, \DOMNode $contextnode = null);

    /**
     * Returns namespaces used in document.
     *
     * @return array Array of namespace names with their associated URIs
     */
    public function getUsedNamespaces();

    /**
     * Adding add-ons in the response object.
     *
     * @param object $addon add-on object
     */
    public function addExtAddon($addon);

    /**
     * Removing add-on by class name.
     *
     * @param string $classname class name
     */
    public function removeExtAddon($classname);

    /**
     * Find add-on by class name.
     *
     * @param string $classname class name
     *
     * @return object|null
     */
    public function findExtAddon($classname);
}
