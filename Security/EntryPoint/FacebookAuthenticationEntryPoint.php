<?php

namespace FOS\FacebookBundle\Security\EntryPoint;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * FacebookAuthenticationEntryPoint starts an authentication via Facebook.
 *
 * @author Thomas Adam <thomas.adam@tebot.de>
 */
class FacebookAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    protected $facebook;
    protected $options;

    /**
     * Constructor
     *
     * @param Facebook $facebook
     * @param array    $options
     */
    public function __construct(\Facebook $facebook, array $options = array())
    {
        $this->facebook = $facebook;
        $this->options = new ParameterBag($options);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $response = new Response();
        $response->setRedirect($this->facebook->getLoginUrl(array(
                    'cancel_url' => $request->getUriForPath($this->options->get('cancel_url', '')),
                    'canvas' => $this->options->get('canvas', 0),
                    'display' => $this->options->get('display', 'page'),
                    'fbconnect' => $this->options->get('fbconnect', 1),
                    'permissions' => implode(',', $this->options->get('permissions', array())),
                    'next' => $request->getUriForPath($this->options->get('next', '')),
                ))
        );

        return $response;
    }
}
