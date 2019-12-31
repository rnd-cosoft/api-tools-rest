<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-rest for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-rest/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-rest/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Rest;

use ArrayAccess;
use Laminas\ApiTools\MvcAuth\Identity\IdentityInterface;
use Laminas\EventManager\Event;
use Laminas\EventManager\Exception\InvalidArgumentException;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Mvc\Router\RouteMatch;
use Laminas\Stdlib\Parameters;
use Laminas\Stdlib\RequestInterface;

class ResourceEvent extends Event
{
    /**
     * @var null|IdentityInterface
     */
    protected $identity;

    /**
     * @var null|InputFilterInterface
     */
    protected $inputFilter;

    /**
     * @var null|Parameters
     */
    protected $queryParams;

    /**
     * @var null|RequestInterface
     */
    protected $request;

    /**
     * @var null|RouteMatch
     */
    protected $routeMatch;

    /**
     * Overload setParams to inject request object, if passed via params
     *
     * @param array|ArrayAccess|object $params
     * @return self
     */
    public function setParams($params)
    {
        if (! is_array($params) && ! is_object($params)) {
            throw new InvalidArgumentException(sprintf(
                'Event parameters must be an array or object; received "%s"',
                gettype($params)
            ));
        }

        if (is_array($params) || $params instanceof ArrayAccess) {
            if (isset($params['request'])) {
                $this->setRequest($params['request']);
                unset($params['request']);
            }
        }

        parent::setParams($params);
        return $this;
    }

    /**
     * @param null|IdentityInterface $identity
     * @return self
     */
    public function setIdentity(IdentityInterface $identity = null)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return null|IdentityInterface
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param null|InputFilterInterface $inputFilter
     * @return self
     */
    public function setInputFilter(InputFilterInterface $inputFilter = null)
    {
        $this->inputFilter = $inputFilter;
        return $this;
    }

    /**
     * @return null|InputFilterInterface
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }

    /**
     * @param Parameters $params
     * @return self
     */
    public function setQueryParams(Parameters $params = null)
    {
        $this->queryParams = $params;
        return $this;
    }

    /**
     * @return null|Parameters
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Retrieve a single query parameter by name
     *
     * If not present, returns the $default value provided.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getQueryParam($name, $default = null)
    {
        $params = $this->getQueryParams();
        if (null === $params) {
            return $default;
        }

        return $params->get($name, $default);
    }

    /**
     * @param null|RequestInterface $request
     * @return self
     */
    public function setRequest(RequestInterface $request = null)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return null|RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param RouteMatch $matches
     * @return self
     */
    public function setRouteMatch(RouteMatch $matches = null)
    {
        $this->routeMatch = $matches;
        return $this;
    }

    /**
     * @return null|RouteMatch
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }

    /**
     * Retrieve a single route match parameter by name.
     *
     * If not present, returns the $default value provided.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getRouteParam($name, $default = null)
    {
        $matches = $this->getRouteMatch();
        if (null === $matches) {
            return $default;
        }

        return $matches->getParam($name, $default);
    }
}