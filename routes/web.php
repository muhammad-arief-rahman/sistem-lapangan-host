<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OpenMatchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TrofeoController;
use App\Mail\BasicMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Route untuk yang belum login
Route::middleware("guest")->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get("/login", "login")->name("login");
        Route::post("/login", "loginPost")->name("login.post");
        Route::get("/register", "register")->name("register");
        Route::post("/register", "registerPost")->name("register.post");
    });
});

// Route untuk yang login (dan belum terverifikasi)
Route::middleware("incomplete-service")->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get("/complete-profile", "completeProfile")->name(
            "complete-profile"
        );
        Route::post("/complete-profile", "completeProfilePost")->name(
            "complete-profile.post"
        );
    });
});

// Route untuk yang login (dan sudah terverifikasi)
Route::middleware("authenticated")->group(function () {
    Route::middleware("authenticated:community")->group(function () {
        Route::controller(LandingController::class)->group(function () {
            Route::get("/", "index")->name("home");
        });

        Route::controller(ServiceController::class)
            ->prefix("jasa")
            ->name("service.")
            ->group(function () {
                Route::get("/{id}", "show")->name("show");
            });

        Route::controller(BookingController::class)
            ->prefix("booking")
            ->name("booking.")
            ->group(function () {
                Route::get("/{id}", "show")->name("show");
                Route::post("/{id}", "store")->name("store");
            });

        Route::name("event.")->group(function () {
            Route::controller(OpenMatchController::class)
                ->prefix("open-matches")
                ->name("open-matches.")
                ->group(function () {
                    Route::get("/", "openMatches")->name("index");
                    Route::get("/{id}", "openMatchDetail")->name("show");
                    Route::post("/{id}", "register")->name("register");
                });

            Route::controller(TrofeoController::class)
                ->prefix("trofeos")
                ->name("trofeos.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::get("/{id}", "show")->name("show");
                    Route::post("/{id}", "store")->name("store");
                });
        });
    });

    Route::prefix("dashboard")
        ->name("dashboard.")
        ->group(function () {
            Route::controller(Dashboard\DashboardController::class)->group(
                function () {
                    Route::get("/", "index")->name("index");
                }
            );

            Route::controller(Dashboard\FieldController::class)
                ->middleware("authenticated:field_manager,super_admin")
                ->prefix("lapangan")
                ->name("field.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::post("/", "store")->name("store");
                    Route::post("/{id}/update", "update")->name("update");
                    Route::post("/{id}/delete", "destroy")->name("delete");
                });

            Route::controller(Dashboard\UserController::class)
                ->middleware("authenticated:super_admin")
                ->prefix("pengguna")
                ->name("user.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::post("/", "store")->name("store");
                    Route::put("/{id}/update", "update")->name("update");
                    Route::post("/{id}/delete", "destroy")->name("delete");
                });

            Route::controller(Dashboard\EventController::class)
                ->prefix("pertandingan")
                ->name("events.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                });

            Route::controller(Dashboard\PaymentController::class)
                ->prefix("pembayaran")
                ->name("payment.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::get("/detail/{id}", "show")->name("show");
                    Route::get("/callback", "callback")->name("callback");
                });
            Route::controller(Dashboard\MutationController::class)
                ->prefix("pendapatan")
                ->name("mutation.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                });
            Route::controller(Dashboard\WithdrawalController::class)
                ->prefix("penarikan")
                ->name("withdrawal.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::post("/store", "store")->name("store");

                    Route::middleware("authenticated:super_admin")->group(
                        function () {
                            Route::post("/process", "process")->name("process");
                            Route::post("/reject", "reject")->name("reject");
                        }
                    );
                });

            Route::controller(Dashboard\BookingController::class)
                ->middleware(
                    "authenticated:field_manager,super_admin,community"
                )
                ->prefix("booking")
                ->name("booking.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                });

            Route::controller(Dashboard\ServiceScheduleController::class)
                ->middleware("authenticated:photographer,referee,super_admin")
                ->prefix("jadwal-layanan")
                ->name("service-schedule.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::post("/", "store")->name("store");
                });

            Route::controller(Dashboard\ProfileController::class)
                ->prefix("profil")
                ->name("profile.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::post("/update-profile", "updateProfile")->name(
                        "update-profile"
                    );

                    Route::middleware(
                        "authenticated:photographer,referee"
                    )->group(function () {
                        Route::post("/update-service", "updateService")->name(
                            "update-service"
                        );
                    });
                });

            Route::controller(Dashboard\NotificationController::class)
                ->prefix("notifikasi")
                ->name("notification.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::post("/read-all", "readAll")->name("read-all");
                    Route::post("/read/{id}", "read")->name("read");
                });

            Route::controller(Dashboard\MatchPhotosController::class)
                ->middleware("authenticated:photographer,super_admin,community")
                ->prefix("foto-pertandingan")
                ->name("match-photos.")
                ->group(function () {
                    Route::get("/", "index")->name("index");
                    Route::get("/{id}", "show")->name("show");

                    Route::middleware("authenticated:photographer")->group(
                        function () {
                            Route::post("/{id}/store", "store")->name("store");
                            Route::post("/{id}/delete", "destroy")->name(
                                "delete"
                            );
                            Route::post(
                                "/{id}/update-link",
                                "updateLink"
                            )->name("update-link");
                        }
                    );
                });

            Route::controller(Dashboard\FieldScheduleController::class)
                ->middleware("authenticated:field_manager")
                ->prefix("jadwal-lapangan")
                ->name("field-schedule.")
                ->group(function () {
                    Route::post("/", "store")->name("store");
                });
        });
});

Route::middleware("auth")->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get("/logout", "logout")->name("logout");
    });
});
