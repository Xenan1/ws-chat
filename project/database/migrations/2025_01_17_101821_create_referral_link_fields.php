<?php

use App\Services\UserService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\User::class, 'referrer_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('referral_link')->nullable();
        });

        $userService = app(UserService::class);
        foreach (\App\Models\User::all() as $user) {
            $referralLink = $userService->generateReferralLink($user->getLogin());
            $userService->setReferralLink($user, $referralLink);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_link')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referrer_id']);
        });
    }
};
