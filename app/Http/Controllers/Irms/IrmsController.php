<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\IrmsSite;

class IrmsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userLevel = $user->level;
        $userSite = $user->rssite;

        $site = IrmsSite::where('rssite', $userSite)->first();
        $userSiteDesc = $site ? $site->rssite_desc : $userSite;


        $dashboardData = $this->getDashboardData($userLevel, $userSite);

        return view('irms.irms-layouts.dashboard', compact('dashboardData', 'userLevel', 'userSite', 'userSiteDesc'));
    }

    public static function getSiteDesc()
    {
        $rssite = Auth::user()->rssite ?? null;
        $site = IrmsSite::where('rssite', $rssite)->first();
        return $site ? $site->rssite_desc : ($rssite ?? 'IRMS');
    }

    public static function getSiteImage()
    {
        $rssite = Auth::user()->rssite ?? null;
        $site = IrmsSite::where('rssite', $rssite)->first();
        $logo_pic_url = $site ? $site->logo_pic_url : 'uploads/sites-img/no-logo.png';
        if (!file_exists(public_path($logo_pic_url)) || !$logo_pic_url) {
            $logo_pic_url = 'uploads/sites-img/no-logo.png';
        }
        return $logo_pic_url;
    }

    public function refreshDashboardData(Request $request)
    {
        $user = Auth::user();
        $userLevel = $user->level;
        $userSite = $user->rssite;

        $dashboardData = $this->getDashboardData($userLevel, $userSite);

        // Render recent transactions HTML
        $recentHtml = view('irms.irms-partials.recent-transactions', [
            'recent_transactions' => $dashboardData['recent_transactions']
        ])->render();

        return response()->json([
            'success' => true,
            'data' => array_merge($dashboardData, [
                'recent_transactions_html' => $recentHtml
            ]),
        ]);
    }

    private function getDashboardData($userLevel, $userSite)
    {
        $data = [];

        try {
            switch ($userLevel) {
                case 1: // Super Admin
                    $data = $this->getSuperAdminData($userSite);
                    break;
                case 2: // Admin
                    $data = $this->getAdminData($userSite);
                    break;
                case 3: // Regular User
                    $data = $this->getUserData($userSite);
                    break;
                default:
                    $data = $this->getDefaultData();
                    break;
            }

            // Add common data for all levels
            $data['recent_transactions'] = $this->getRecentTransactions($userLevel, $userSite);
            $data['system_status'] = $this->getSystemStatus();

        } catch (\Exception $e) {
            \Log::error('Dashboard data error: ' . $e->getMessage());
            $data = $this->getDefaultData();
        }

        return $data;
    }

    private function getSuperAdminData($selectedSite)
    {
        // Initialize with default values
        $stats = [
            'total_sites' => 0,
            'total_users' => 0,
            'total_warehouses' => 0,
            'total_locations' => 0,
            'total_transactions' => 0,
            'active_sessions' => 0,
        ];

        $charts = [
            'sites_distribution' => [],
            'monthly_transactions' => [],
            'weekly_operations' => [],
            'user_activity' => [],
        ];

        try {
            // Safe queries with fallbacks
            try {
                $stats['total_sites'] = DB::table('irms_site')->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count sites: ' . $e->getMessage());
            }

            try {
                $stats['total_users'] = DB::table('rsusers')->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count users: ' . $e->getMessage());
            }

            try {
                $stats['total_warehouses'] = DB::table('rswhse')->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count warehouses: ' . $e->getMessage());
            }

            try {
                $stats['total_locations'] = DB::table('rslocation')->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count locations: ' . $e->getMessage());
            }

            try {
                $stats['total_transactions'] = DB::table('rstrans')->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count transactions: ' . $e->getMessage());
            }

            try {
                $stats['active_sessions'] = DB::table('sessions')
                    ->where('last_activity', '>', now()->subMinutes(30)->timestamp)
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count sessions: ' . $e->getMessage());
            }

            // Get chart data
            try {
                $charts['sites_distribution'] = $this->getSitesDistribution();
            } catch (\Exception $e) {
                \Log::warning('Could not get sites distribution: ' . $e->getMessage());
            }

            try {
                $charts['monthly_transactions'] = $this->getMonthlyTransactions($selectedSite);
            } catch (\Exception $e) {
                \Log::warning('Could not get monthly transactions: ' . $e->getMessage());
            }

            try {
                $charts['user_activity'] = $this->getUserActivity();
            } catch (\Exception $e) {
                \Log::warning('Could not get user activity: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Log::error('Super Admin data error: ' . $e->getMessage());
        }

        return [
            'stats' => $stats,
            'charts' => $charts,
            'quick_actions' => [
                ['icon' => 'fas fa-building', 'label' => 'Manage Sites', 'url' => route('sites.index')],
                ['icon' => 'fas fa-users', 'label' => 'Manage Users', 'url' => route('rsusers.index')],
                ['icon' => 'fas fa-warehouse', 'label' => 'Warehouses', 'url' => route('warehouse.index')],
                ['icon' => 'fas fa-map-marker-alt', 'label' => 'Rack Locations', 'url' => route('racklocations.index')],
                ['icon' => 'fas fa-chart-bar', 'label' => 'Transactions', 'url' => route('irms.transactions')],
                ['icon' => 'fas fa-cogs', 'label' => 'System Settings', 'url' => route('dashboard')],
                ['icon' => 'fas fa-file-alt', 'label' => 'Reports', 'children' => [
                ['label' => 'Stickering Report', 'url' => route('irms.stickering-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ['label' => 'Quarantine Report','url' => route('irms.quarantine-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ['label' => 'WIP Report', 'url' => route('irms.wip-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ]
            ],
        ],
    ];
    }
    private function getAdminData($userSite)
    {
        // Initialize with default values
        $stats = [
            'site_users' => 0,
            'site_warehouses' => 0,
            'site_locations' => 0,
            'monthly_receiving' => 0,
            'monthly_dispatching' => 0,
            'occupied_locations' => 0,
        ];

        $charts = [
            'warehouse_occupancy' => [],
            'monthly_transactions' => [],
            'weekly_operations' => [],
            'today_operations' => [],
        ];

        try {
            // Safe queries with fallbacks
            try {
                $stats['site_users'] = DB::table('rsusers')
                    ->where('rssite', $userSite)
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count site users: ' . $e->getMessage());
            }

            try {
                $stats['site_warehouses'] = DB::table('rswhse')
                    ->where('rssite', $userSite)
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count site warehouses: ' . $e->getMessage());
            }

            try {
                $stats['site_locations'] = DB::table('rslocation')
                    ->where('rssite', $userSite)
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count site locations: ' . $e->getMessage());
            }

            try {
                $stats['monthly_receiving'] = DB::table('rstrans')
                    ->where('rssite', $userSite)
                    ->where('trxtype', 'R')
                    ->whereMonth('createdate', Carbon::now()->month)
                    ->whereYear('createdate', Carbon::now()->year)
                    ->sum('qty') ?? 0;
            } catch (\Exception $e) {
                \Log::warning('Could not count monthly receiving: ' . $e->getMessage());
            }

            try {
                $stats['monthly_dispatching'] = DB::table('rstrans')
                    ->where('rssite', $userSite)
                    ->where('trxtype', 'D')
                    ->whereMonth('createdate', Carbon::now()->month)
                    ->whereYear('createdate', Carbon::now()->year)
                    ->sum('qty') ?? 0;
            } catch (\Exception $e) {
                \Log::warning('Could not count monthly dispatching: ' . $e->getMessage());
            }

            try {
                $stats['occupied_locations'] = DB::table('rsitemloc')
                    ->where('rssite', $userSite)
                    ->where('qty', '>', 0)
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count occupied locations: ' . $e->getMessage());
            }

            // Get chart data
            try {
                $charts['warehouse_occupancy'] = $this->getWarehouseOccupancy($userSite);
            } catch (\Exception $e) {
                \Log::warning('Could not get warehouse occupancy: ' . $e->getMessage());
            }

            try {
                $charts['monthly_transactions'] = $this->getMonthlyTransactions($userSite);
            } catch (\Exception $e) {
                \Log::warning('Could not get monthly transactions: ' . $e->getMessage());
            }

            try {
                $charts['weekly_operations'] = $this->getWeeklyOperations($userSite);
            } catch (\Exception $e) {
                \Log::warning('Could not get weekly operations: ' . $e->getMessage());
            }

            try {
                $charts['today_operations'] = $this->getTodayOperations($userSite);
            } catch (\Exception $e) {
                \Log::warning('Could not get today operations: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Log::error('Admin data error: ' . $e->getMessage());
        }

        return [
            'stats' => $stats,
            'charts' => $charts,
            'quick_actions' => [
                ['icon' => 'fas fa-warehouse', 'label' => 'Warehouses', 'url' => route('warehouse.index')],
                ['icon' => 'fas fa-download', 'label' => 'Goods Receiving', 'url' => route('goodsreceiving.index')],
                ['icon' => 'fas fa-upload', 'label' => 'Goods Dispatching', 'url' => route('goodsdispatching.index')],
                ['icon' => 'bi bi-grid-3x3', 'label' => 'Rack Locations', 'url' => route('racklocations.index')],
                ['icon' => 'fas fa-boxes', 'label' => 'Item Locations', 'url' => route('irms.itemlocations')],
                ['icon' => 'fas fa-exchange-alt', 'label' => 'Transactions', 'url' => route('irms.transactions')],
                ['icon' => 'fas fa-file-alt', 'label' => 'Reports', 'children' => [
                ['label' => 'Stickering Report', 'url' => route('irms.stickering-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ['label' => 'Quarantine Report','url' => route('irms.quarantine-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ['label' => 'WIP Report', 'url' => route('irms.wip-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ]
            ],
        ],
     ];
    }

    private function getUserData($userSite)
    {
        // Initialize with default values
        $stats = [
            'available_locations' => 0,
            'occupied_locations' => 0,
            'today_receiving' => 0,
            'today_dispatching' => 0,
        ];

        $charts = [
            'monthly_transactions' => [],
            'weekly_operations' => [],
            'today_operations' => [],
        ];

        try {
            // Safe queries with fallbacks
            try {
                $stats['available_locations'] = DB::table('rslocation')
                    ->where('rssite', $userSite)
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('rsitemloc')
                            ->whereRaw('rsitemloc.rsloc = rslocation.rsloc')
                            ->where('qty', '>', 0);
                    })
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count available locations: ' . $e->getMessage());
            }

            try {
                $stats['occupied_locations'] = DB::table('rsitemloc')
                    ->where('rssite', $userSite)
                    ->where('qty', '>', 0)
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Could not count occupied locations: ' . $e->getMessage());
            }

            try {
                $stats['today_receiving'] = DB::table('rstrans')
                    ->where('rssite', $userSite)
                    ->where('trxtype', 'R')
                    ->whereDate('createdate', Carbon::today())
                    ->sum('qty') ?? 0;
            } catch (\Exception $e) {
                \Log::warning('Could not count today receiving: ' . $e->getMessage());
            }

            try {
                $stats['today_dispatching'] = DB::table('rstrans')
                    ->where('rssite', $userSite)
                    ->where('trxtype', 'D')
                    ->whereDate('createdate', Carbon::today())
                    ->sum('qty') ?? 0;
            } catch (\Exception $e) {
                \Log::warning('Could not count today dispatching: ' . $e->getMessage());
            }

            // Get chart data
            try {
                $charts['monthly_transactions'] = $this->getMonthlyTransactions($userSite);
            } catch (\Exception $e) {
                \Log::warning('Could not get monthly transactions: ' . $e->getMessage());
            }

            try {
                $charts['weekly_operations'] = $this->getWeeklyOperations($userSite);
            } catch (\Exception $e) {
                \Log::warning('Could not get weekly operations: ' . $e->getMessage());
            }

            try {
                $charts['today_operations'] = $this->getTodayOperations($userSite);
            } catch (\Exception $e) {
                \Log::warning('Could not get weekly operations: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Log::error('User data error: ' . $e->getMessage());
        }

        return [
            'stats' => $stats,
            'charts' => $charts,
            'quick_actions' => [
                ['icon' => 'fas fa-download', 'label' => 'Goods Receiving', 'url' => route('goodsreceiving.index')],
                ['icon' => 'fas fa-upload', 'label' => 'Goods Dispatching', 'url' => route('goodsdispatching.index')],
                ['icon' => 'bi bi-grid-3x3', 'label' => 'Rack Locations', 'url' => route('racklocations.index')],
                ['icon' => 'fas fa-boxes', 'label' => 'Item Locations', 'url' => route('irms.itemlocations')],
                ['icon' => 'fas fa-exchange-alt', 'label' => 'Transactions', 'url' => route('irms.transactions')],
                ['icon' => 'fas fa-file-alt', 'label' => 'Reports', 'children' => [
                ['label' => 'Stickering Report', 'url' => route('irms.stickering-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ['label' => 'Quarantine Report','url' => route('irms.quarantine-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ['label' => 'WIP Report', 'url' => route('irms.wip-report.preview'),'target' => '_blank', 'autoDownload' => false],
                ]
            ],
        ],
    ];
    }

    private function getDefaultData()
    {
        return [
            'stats' => [
                'message' => 'Welcome to IRMS Dashboard',
                'status' => 'System is operational'
            ],
            'quick_actions' => [
                ['icon' => 'fas fa-home', 'label' => 'Dashboard', 'url' => route('dashboard')],
                ['icon' => 'fas fa-user', 'label' => 'Profile', 'url' => route('irms.userprofile', ['userid' => auth()->user()->userid])],
            ],
            'recent_transactions' => [],
            'system_status' => []
        ];
    }

    private function getRecentTransactions($userLevel, $userSite)
    {
        try {

            $query = DB::table('rstrans')
                ->join('rsusers', 'rstrans.createdby', '=', 'rsusers.userid')
                ->select('trxtype', 'job', 'item', 'qty', 'createdate', 'createdby', 'rsusers.name')
                ->orderBy('createdate', 'desc')
                ->limit(5);
            if ($userLevel != 1) { // Not Super Admin
                $query->where('rstrans.rssite', $userSite);
            }
            if($userLevel == 3) {
                $query->where('createdby', Auth::user()->userid);
            }

            return $query->get()->map(function ($activity) {
                if (auth()->user()->level == 3 ){
                    return [
                        'icon' => $activity->trxtype == 'R' ? 'fas fa-download' : 'fas fa-upload',
                        'type' => $activity->trxtype == 'R' ? 'primary' : 'warning',
                        'title' => ucfirst(strtolower($activity->trxtype == 'R' ? 'Received' : 'Dispatched')) . ' - ' . $activity->job . ': ' . $activity->item,
                        'description' => 'Qty: ' . number_format($activity->qty),
                        'time' => Carbon::parse($activity->createdate)->diffForHumans()
                    ];
                } else {
                    return [
                        'icon' => $activity->trxtype == 'R' ? 'fas fa-download' : 'fas fa-upload',
                        'type' => $activity->trxtype == 'R' ? 'primary' : 'warning',
                        'title' => ucfirst(strtolower($activity->trxtype == 'R' ? 'Received' : 'Dispatched')) . ' - ' . $activity->job . ': ' . $activity->item,
                        'description' => 'Qty: ' . number_format($activity->qty) . ' by ' . $activity->name,
                        'time' => Carbon::parse($activity->createdate)->diffForHumans()
                    ];
                }
            })->toArray();
        } catch (\Exception $e) {
            \Log::warning('Could not get recent activities: ' . $e->getMessage());
            return [];
        }
    }

    private function getSystemStatus()
    {
        try {
            $status = [];

            // Test database connection
            try {
                DB::connection()->getPdo();
                $status[] = ['label' => 'Database', 'status' => 'online'];
            } catch (\Exception $e) {
                $status[] = ['label' => 'Database', 'status' => 'offline'];
            }

            // Test session
            try {
                if (session()->isStarted()) {
                    $status[] = ['label' => 'Session Management', 'status' => 'online'];
                } else {
                    $status[] = ['label' => 'Session Management', 'status' => 'warning'];
                }
            } catch (\Exception $e) {
                $status[] = ['label' => 'Session Management', 'status' => 'offline'];
            }

            // Test file storage
            try {
                if (is_writable(storage_path())) {
                    $status[] = ['label' => 'File Storage', 'status' => 'online'];
                } else {
                    $status[] = ['label' => 'File Storage', 'status' => 'warning'];
                }
            } catch (\Exception $e) {
                $status[] = ['label' => 'File Storage', 'status' => 'offline'];
            }

            // Check email service
            // try {
            //     \Mail::raw('Test email from IRMS system status check.', function ($message) {
            //         $message->to(config('mail.from.address'))
            //                 ->subject('IRMS Email Service Test');
            //     });
            //     $status[] = ['label' => 'Email Service', 'status' => 'online'];
            // } catch (\Exception $e) {
            //     $status[] = ['label' => 'Email Service', 'status' => 'offline'];
            // }

            return $status;
        } catch (\Exception $e) {
            return [
                ['label' => 'System', 'status' => 'offline']
            ];
        }
    }

    private function getSitesDistribution()
    {
        try {
            return DB::table('irms_site')
                ->select('rssite_desc', DB::raw('count(*) as count'))
                ->join('rsusers', 'irms_site.rssite', '=', 'rsusers.rssite')
                ->groupBy('irms_site.rssite', 'rssite_desc')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning('Could not get sites distribution: ' . $e->getMessage());
            return [];
        }
    }

    private function getWarehouseOccupancy($userSite)
    {
        try {
            return DB::table('rswhse')
                ->select(
                    'rswhse_desc',
                    DB::raw('(SELECT COUNT(*) FROM rslocation WHERE rslocation.rswhse = rswhse.rswhse) as total_locations'),
                    DB::raw('(SELECT COUNT(*) FROM rsitemloc WHERE rsitemloc.rswhse = rswhse.rswhse AND qty > 0) as occupied_locations')
                )
                ->where('rssite', $userSite)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning('Could not get warehouse occupancy: ' . $e->getMessage());
            return [];
        }
    }

    private function getMonthlyTransactions($userSite)
    {
        try {
            return DB::table('rstrans')
                ->select(
                    DB::raw("MONTH(createdate) as month"),
                    DB::raw("SUM(CASE WHEN trxtype = 'R' THEN qty ELSE 0 END) as received"),
                    DB::raw("SUM(CASE WHEN trxtype = 'D' THEN qty ELSE 0 END) as dispatched")
                )
                ->whereYear('createdate', Carbon::now()->year)
                ->where('rssite', $userSite)
                ->groupBy(DB::raw("MONTH(createdate)"))
                ->orderBy('month')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning('Could not get monthly transactions: ' . $e->getMessage());
            return [];
        }
    }

    private function getWeeklyOperations($userSite)
    {
        try {
            return DB::table('rstrans')
                ->select(
                    DB::raw("DATEPART(WEEKDAY, createdate) as weekday"),
                    DB::raw("SUM(CASE WHEN trxtype = 'R' THEN qty ELSE 0 END) as received"),
                    DB::raw("SUM(CASE WHEN trxtype = 'D' THEN qty ELSE 0 END) as dispatched")
                )
                ->where('rssite', $userSite)
                ->where('createdate', '>=', Carbon::now()->startOfWeek()->startOfDay())
                ->groupBy(DB::raw("DATEPART(WEEKDAY, createdate)"))
                ->orderBy('weekday')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            \Log::warning('Could not get weekly operations: ' . $e->getMessage());
            return [];
        }
    }

    private function getTodayOperations($userSite)
    {
        try {
            return DB::table('rstrans')
                ->select(
                    DB::raw("SUM(CASE WHEN trxtype = 'R' THEN qty ELSE 0 END) as received"),
                    DB::raw("SUM(CASE WHEN trxtype = 'D' THEN qty ELSE 0 END) as dispatched")
                )
                ->where('rssite', $userSite)
                ->whereDate('createdate', Carbon::today())
                ->first();
        } catch (\Exception $e) {
            \Log::warning('Could not get today operations: ' . $e->getMessage());
            return (object) ['received' => 0, 'dispatched' => 0];
        }
    }
}