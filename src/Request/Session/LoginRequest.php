<?php

namespace Struzik\EPPClient\Request\Session;

use Struzik\EPPClient\NamespaceCollection;
use Struzik\EPPClient\Node\Common\CommandNode;
use Struzik\EPPClient\Node\Common\EppNode;
use Struzik\EPPClient\Node\Common\TransactionIdNode;
use Struzik\EPPClient\Node\Session\ClientIdNode;
use Struzik\EPPClient\Node\Session\ExtensionURINode;
use Struzik\EPPClient\Node\Session\ExtNamespacesNode;
use Struzik\EPPClient\Node\Session\LanguageNode;
use Struzik\EPPClient\Node\Session\LoginNode;
use Struzik\EPPClient\Node\Session\NamespacesNode;
use Struzik\EPPClient\Node\Session\NewPasswordNode;
use Struzik\EPPClient\Node\Session\ObjectURINode;
use Struzik\EPPClient\Node\Session\OptionsNode;
use Struzik\EPPClient\Node\Session\PasswordNode;
use Struzik\EPPClient\Node\Session\VersionNode;
use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Session\LoginResponse;

/**
 * Object representation of the request of login command.
 */
class LoginRequest extends AbstractRequest
{
    private string $login = '';
    private string $password = '';
    private ?string $newPassword = null;
    private string $protocolVersion = '';
    private string $language = '';

    /**
     * {@inheritdoc}
     */
    protected function handleParameters(): void
    {
        $eppNode = EppNode::create($this);
        $commandNode = CommandNode::create($this, $eppNode);
        $loginNode = LoginNode::create($this, $commandNode);
        ClientIdNode::create($this, $loginNode, $this->login);
        PasswordNode::create($this, $loginNode, $this->password);
        if ($this->newPassword !== null) {
            NewPasswordNode::create($this, $loginNode, $this->newPassword);
        }
        $optionsNode = OptionsNode::create($this, $loginNode);
        VersionNode::create($this, $optionsNode, $this->protocolVersion);
        LanguageNode::create($this, $optionsNode, $this->language);
        $namespacesNode = NamespacesNode::create($this, $loginNode);
        foreach ($this->getClient()->getNamespaceCollection() as $name => $uri) {
            if ($name === NamespaceCollection::NS_NAME_ROOT) {
                continue;
            }
            ObjectURINode::create($this, $namespacesNode, $uri);
        }
        if (count($this->getClient()->getExtNamespaceCollection()) > 0) {
            $extNamespacesNode = ExtNamespacesNode::create($this, $namespacesNode);
            foreach ($this->getClient()->getExtNamespaceCollection() as $uri) {
                ExtensionURINode::create($this, $extNamespacesNode, $uri);
            }
        }
        TransactionIdNode::create($this, $commandNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass(): string
    {
        return LoginResponse::class;
    }

    /**
     * Setting the value of the login parameter. REQUIRED.
     *
     * @param string $login user login
     */
    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Getting the value of the login parameter.
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Setting the value of the password parameter. REQUIRED.
     *
     * @param string $password user password
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the value of the password parameter.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setting the value of the new password parameter. OPTIONAL.
     *
     * @param string|null $newPassword new user password
     */
    public function setNewPassword(string $newPassword = null): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Getting the value of the new password parameter.
     */
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    /**
     * Setting the protocol version to be used for the command
     * or ongoing server session. REQUIRED.
     *
     * @param string $protocolVersion protocol version
     */
    public function setProtocolVersion(string $protocolVersion): self
    {
        $this->protocolVersion = $protocolVersion;

        return $this;
    }

    /**
     * Getting the protocol version to be used for the command
     * or ongoing server session.
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * Setting the text response language to be used for the command
     * or ongoing server session commands. REQUIRED.
     *
     * @param string $language language code
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Getting the text response language to be used for the command
     * or ongoing server session commands.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}
