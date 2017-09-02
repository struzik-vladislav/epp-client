<?php

namespace Struzik\EPPClient\Request\Session;

use Struzik\EPPClient\Request\AbstractRequest;
use Struzik\EPPClient\Response\Session\Login as LoginResponse;
use Struzik\EPPClient\Node\Common\Command;
use Struzik\EPPClient\Node\Common\TransactionId;
use Struzik\EPPClient\Node\Session\Login as LoginNode;
use Struzik\EPPClient\Node\Session\ClientId;
use Struzik\EPPClient\Node\Session\Options;
use Struzik\EPPClient\Node\Session\Version;
use Struzik\EPPClient\Node\Session\Password;
use Struzik\EPPClient\Node\Session\Language;
use Struzik\EPPClient\Node\Session\ObjectURI;
use Struzik\EPPClient\Node\Session\Namespaces;
use Struzik\EPPClient\Node\Session\NewPassword;
use Struzik\EPPClient\Node\Session\ExtensionURI;
use Struzik\EPPClient\Node\Session\ExtNamespaces;
use Struzik\EPPClient\NamespaceCollection;

/**
 * Object representation of the request of login command.
 */
class Login extends AbstractRequest
{
    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $newPassword;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $language;

    /**
     * {@inheritdoc}
     */
    protected function handleParameters()
    {
        $epp = $this->getRoot();

        $command = new Command($this);
        $epp->append($command);

        $login = new LoginNode($this);
        $command->append($login);

        $clientId = new ClientId($this, ['login' => $this->getLogin()]);
        $login->append($clientId);

        $password = new Password($this, ['password' => $this->getPassword()]);
        $login->append($password);

        if ($this->getNewPassword() !== null) {
            $password = new NewPassword($this, ['new-password' => $this->getNewPassword()]);
            $login->append($password);
        }

        $options = new Options($this);
        $login->append($options);

        $version = new Version($this, ['version' => $this->getVersion()]);
        $options->append($version);

        $language = new Language($this, ['language' => $this->getLanguage()]);
        $options->append($language);

        $namespaces = new Namespaces($this);
        $login->append($namespaces);

        foreach ($this->getClient()->getNamespaceCollection() as $name => $uri) {
            if ($name === NamespaceCollection::NS_NAME_ROOT) {
                continue;
            }
            $objectURI = new ObjectURI($this, ['uri' => $uri]);
            $namespaces->append($objectURI);
        }

        if (count($this->getClient()->getExtNamespaceCollection()) > 0) {
            $namespaceExtension = new ExtNamespaces($this);
            $namespaces->append($namespaceExtension);

            foreach ($this->getClient()->getExtNamespaceCollection() as $uri) {
                $extensionURI = new ExtensionURI($this, ['uri' => $uri]);
                $namespaceExtension->append($extensionURI);
            }
        }

        $transaction = new TransactionId($this);
        $command->append($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseClass()
    {
        return LoginResponse::class;
    }

    /**
     * Setting the value of the login parameter. REQUIRED.
     *
     * @param string $login user login
     *
     * @return self
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Getting the value of the login parameter.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Setting the value of the password parameter. REQUIRED.
     *
     * @param string $password user password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Getting the value of the password parameter.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Setting the value of the new password parameter. OPTIONAL.
     *
     * @param string|null $newPassword new user password
     *
     * @return self
     */
    public function setNewPassword($newPassword = null)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Getting the value of the new password parameter.
     *
     * @return string|null
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * Setting the protocol version to be used for the command
     * or ongoing server session. REQUIRED.
     *
     * @param string $version protocol version
     *
     * @return self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Getting the protocol version to be used for the command
     * or ongoing server session.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Setting the text response language to be used for the command
     * or ongoing server session commands. REQUIRED.
     *
     * @param string $language language code
     *
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Getting the text response language to be used for the command
     * or ongoing server session commands.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
