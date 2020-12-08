<?php

declare(strict_types=1);

namespace Reconmap\Controllers\Reports;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reconmap\Controllers\Controller;
use Reconmap\Services\Config;
use Reconmap\Services\ConfigConsumer;
use Reconmap\Services\ReportGenerator;

class GetReportPreviewController extends Controller implements ConfigConsumer
{
    private ?Config $config = null;

    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $params = $request->getQueryParams();
        $projectId = (int)$params['projectId'];

        $reportGenerator = new ReportGenerator($this->config, $this->db, $this->template);
        $html = $reportGenerator->generate($projectId);

        $response = new Response;
        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-type', 'text/html');
    }
}
