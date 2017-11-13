<?php
declare(strict_types = 1);
/**
 * Weave Router Adaptor for FastRoute.
 */
namespace Weave\Router\FastRoute;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Weave Router Adaptor for FastRoute.
 */
class FastRoute implements \Weave\Router\RouterAdaptorInterface
{
	/**
	 * The FastRoute\Dispatcher instance.
	 *
	 * @var \FastRoute\Dispatcher
	 */
	protected $dispatcher;

	/**
	 * Using the provided callable, configure the routes.
	 *
	 * @param callable $routeProvider The method to use to configure the routes.
	 *
	 * @return null
	 */
	public function configureRoutes(callable $routeProvider)
	{
		$this->dispatcher = \FastRoute\simpleDispatcher($routeProvider);
	}

	/**
	 * Route the supplied request.
	 *
	 * @param Request &$request The PSR7 request to attempt to route.
	 *
	 * @return false|string|callable
	 */
	public function route(Request &$request)
	{
		$routeInfo = $this->dispatcher->dispatch(
			$request->getMethod(),
			$request->getUri()->getPath()
		);

		if ($routeInfo[0] !== \FastRoute\Dispatcher::FOUND) {
			return false;
		}

		foreach ($routeInfo[2] as $key => $val) {
			$request = $request->withAttribute($key, $val);
		}
		return $routeInfo[1];
	}
}
