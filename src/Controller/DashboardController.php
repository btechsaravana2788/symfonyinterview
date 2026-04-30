<?php

namespace App\Controller;

use App\Application\Dashboard\Command\PopulateDashboardCommand;
use App\Application\Dashboard\Query\DashboardQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class DashboardController extends AbstractController
{
    public function __construct(private readonly DashboardQueryService $dashboardQueryService)
    {
    }

    #[Route('/dashboard/{page<\d+>?1}', name: 'dashboard_table', methods: ['GET'])]
    public function index(int $page, Request $request): JsonResponse
    {
        $pageSize = (int) $request->query->get('pageSize', 25);
        $view = $this->dashboardQueryService->fetchDashboardPage($page, $pageSize);

        return $this->json($view->toArray());
    }

    #[Route('/dashboard/warmup', name: 'dashboard_warmup', methods: ['POST'])]
    public function warmup(MessageBusInterface $bus): JsonResponse
    {
        $bus->dispatch(new PopulateDashboardCommand());

        return $this->json(['status' => 'accepted', 'message' => 'Dashboard warmup dispatched']);
    }
}
