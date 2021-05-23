<?php declare(strict_types=1);

namespace Reconmap\Controllers\Reports;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reconmap\Controllers\Controller;
use Reconmap\Services\ReportGenerator;

class GetReportPreviewController extends Controller
{
    public function __construct(private ReportGenerator $reportGenerator)
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $projectId = (int)$params['projectId'];

        $html = $this->reportGenerator->generate($projectId);

        $response = new Response;
        $response->getBody()->write($html['body']);
        return $response
            ->withHeader('Content-type', 'text/html');
    }
}
