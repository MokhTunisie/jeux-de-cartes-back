<?php

namespace App\Tests\Infrastructure\EventListener;

use App\Domain\Exception\InvalidCardAttributeException;
use App\Infrastructure\EventListener\ExceptionListener;
use DG\BypassFinals;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ExceptionListenerTest extends TestCase
{
    private MockObject $serializer;
    private ExceptionListener $exceptionListener;

    protected function setUp(): void
    {
//        BypassFinals::enable();
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->exceptionListener = new ExceptionListener($this->serializer);
    }

    public function testInvokeWithHttpException(): void
    {
        $exception = $this->createMock(HttpExceptionInterface::class);
        $exception->method('getStatusCode')->willReturn(404);
        $exception->method('getHeaders')->willReturn(['Content-Type' => 'application/json']);

        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );
        $event->setThrowable($exception);

        $this->exceptionListener->__invoke($event);

        $response = $event->getResponse();
        $this->assertEquals(404, $response?->getStatusCode());
        $this->assertEquals('Exception : ', $response?->getContent());
        $this->assertEquals('application/json', $response?->headers->get('Content-Type'));
    }

    public function testInvokeWithDomainException(): void
    {
        $exception = new InvalidCardAttributeException();
        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );
        $event->setThrowable($exception);

        $this->serializer->method('serialize')->willReturn(json_encode([
            'code' => 400,
            'message' => 'Invalid card attribute: color or value is not valid.',
            'title' => 'Domain Exception :: InvalidCardAttributeException',
        ]));

        $this->exceptionListener->__invoke($event);

        $response = $event->getResponse();
        $this->assertEquals(400, $response?->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            'code' => 400,
            'message' => 'Invalid card attribute: color or value is not valid.',
            'title' => 'Domain Exception :: InvalidCardAttributeException',
        ]) ?: '', $response?->getContent() ?: '');
    }

    public function testInvokeWithGenericException(): void
    {
        $exception = new \Exception('Generic error');
        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );
        $event->setThrowable($exception);

        $this->exceptionListener->__invoke($event);

        $response = $event->getResponse();
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response?->getStatusCode());
        $this->assertEquals('Exception : Generic error', $response?->getContent());
    }
}