<?php

// Script to populate Laravel migrations with proper schema

$migrations = [
    'profiles' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->string('avatar_url')->nullable();
            \$table->json('preferences_json')->default('{}');
            \$table->timestamps();
        "
    ],
    'pavilions' => [
        'schema' => "
            \$table->id();
            \$table->string('name', 160);
            \$table->text('description')->nullable();
            \$table->decimal('lat', 9, 6)->nullable();
            \$table->decimal('lng', 9, 6)->nullable();
            \$table->string('open_hours', 160)->nullable();
            \$table->timestamps();
        "
    ],
    'shops' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('pavilion_id')->constrained()->onDelete('cascade');
            \$table->string('name', 160);
            \$table->text('description')->nullable();
            \$table->string('type', 32)->default('shop');
            \$table->timestamps();
        "
    ],
    'products' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('shop_id')->constrained()->onDelete('cascade');
            \$table->string('name', 160);
            \$table->text('description')->nullable();
            \$table->decimal('price', 10, 2);
            \$table->string('image_url')->nullable();
            \$table->timestamps();
        "
    ],
    'product_tags' => [
        'schema' => "
            \$table->id();
            \$table->string('name', 60)->unique();
            \$table->timestamps();
        "
    ],
    'product_tag_maps' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('product_id')->constrained()->onDelete('cascade');
            \$table->foreignId('tag_id')->constrained('product_tags')->onDelete('cascade');
            \$table->timestamps();

            \$table->unique(['product_id', 'tag_id']);
        "
    ],
    'offers' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null');
            \$table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            \$table->string('title', 160);
            \$table->text('description')->nullable();
            \$table->string('discount_type', 16);
            \$table->decimal('value', 10, 2);
            \$table->boolean('is_bundle')->default(false);
            \$table->timestamp('start_at');
            \$table->timestamp('end_at');
            \$table->timestamps();

            \$table->index(['start_at', 'end_at']);
        "
    ],
    'offer_redemptions' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('offer_id')->constrained()->onDelete('cascade');
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->string('qr_code', 128);
            \$table->string('status', 16)->default('issued');
            \$table->timestamp('expires_at')->nullable();
            \$table->timestamp('redeemed_at')->nullable();
            \$table->timestamps();

            \$table->unique(['offer_id', 'user_id', 'qr_code']);
            \$table->index(['user_id', 'status']);
        "
    ],
    'events' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('pavilion_id')->nullable()->constrained()->onDelete('set null');
            \$table->string('title', 160);
            \$table->text('description')->nullable();
            \$table->string('stage', 160)->nullable();
            \$table->decimal('price', 10, 2)->nullable()->default(-1.00);
            \$table->timestamp('start_time');
            \$table->timestamp('end_time');
            \$table->timestamps();
        "
    ],
    'event_tags' => [
        'schema' => "
            \$table->id();
            \$table->string('name', 60)->unique();
            \$table->timestamps();
        "
    ],
    'event_tag_maps' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('event_id')->constrained()->onDelete('cascade');
            \$table->foreignId('tag_id')->constrained('event_tags')->onDelete('cascade');
            \$table->timestamps();

            \$table->unique(['event_id', 'tag_id']);
        "
    ],
    'event_attendance' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('event_id')->constrained()->onDelete('cascade');
            \$table->string('status', 16);
            \$table->timestamp('reminder_at')->nullable();
            \$table->timestamp('checked_in_at')->nullable();
            \$table->timestamps();

            \$table->unique(['user_id', 'event_id']);
            \$table->index(['event_id', 'status']);
        "
    ],
    'orders' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('shop_id')->constrained()->onDelete('restrict');
            \$table->decimal('total_amount', 10, 2);
            \$table->string('status', 16)->default('pending');
            \$table->timestamps();

            \$table->index(['user_id', 'status', 'created_at']);
        "
    ],
    'order_items' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('order_id')->constrained()->onDelete('cascade');
            \$table->foreignId('product_id')->constrained()->onDelete('restrict');
            \$table->integer('qty')->default(1);
            \$table->decimal('unit_price', 10, 2);
            \$table->timestamps();

            \$table->unique(['order_id', 'product_id']);
        "
    ],
    'pois' => [
        'schema' => "
            \$table->id();
            \$table->string('type', 20);
            \$table->string('name', 160);
            \$table->decimal('lat', 9, 6);
            \$table->decimal('lng', 9, 6);
            \$table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null');
            \$table->foreignId('pavilion_id')->nullable()->constrained()->onDelete('set null');
            \$table->timestamps();
        "
    ],
    'routes' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->string('name', 120)->default('My Route');
            \$table->timestamps();
        "
    ],
    'route_stops' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('route_id')->constrained()->onDelete('cascade');
            \$table->foreignId('poi_id')->constrained()->onDelete('cascade');
            \$table->integer('sequence');
            \$table->timestamps();

            \$table->unique(['route_id', 'sequence']);
        "
    ],
    'checkins' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('poi_id')->nullable()->constrained()->onDelete('set null');
            \$table->foreignId('event_id')->nullable()->constrained()->onDelete('set null');
            \$table->timestamp('checked_in_at');
            \$table->timestamps();
        "
    ],
    'wallets' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->integer('points')->default(0);
            \$table->string('tier', 12)->default('bronze');
            \$table->timestamps();
        "
    ],
    'rewards' => [
        'schema' => "
            \$table->id();
            \$table->string('title', 160);
            \$table->text('description')->nullable();
            \$table->integer('points_required');
            \$table->timestamps();
        "
    ],
    'reward_redemptions' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('reward_id')->constrained()->onDelete('restrict');
            \$table->timestamp('redeemed_at');
            \$table->timestamps();
        "
    ],
    'reviews' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null');
            \$table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            \$table->tinyInteger('rating');
            \$table->text('comment')->nullable();
            \$table->timestamps();
        "
    ],
    'notifications' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->string('type', 16);
            \$table->text('message');
            \$table->timestamp('read_at')->nullable();
            \$table->timestamps();
        "
    ],
    'interests' => [
        'schema' => "
            \$table->id();
            \$table->string('name', 60)->unique();
            \$table->timestamps();
        "
    ],
    'user_interests' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->foreignId('interest_id')->constrained()->onDelete('cascade');
            \$table->timestamps();

            \$table->unique(['user_id', 'interest_id']);
        "
    ],
    'otp_codes' => [
        'schema' => "
            \$table->id();
            \$table->foreignId('auth_method_id')->constrained()->onDelete('cascade');
            \$table->string('code', 6);
            \$table->timestamp('expires_at');
            \$table->smallInteger('attempts')->default(0);
            \$table->boolean('is_used')->default(false);
            \$table->timestamps();
        "
    ]
];

foreach ($migrations as $table => $config) {
    $filename = glob("/Users/maci/development/workspace/python/CultureConnectLaravel/database/migrations/*_create_{$table}_table.php")[0];
    $content = file_get_contents($filename);

    $newSchema = "Schema::create('{$table}', function (Blueprint \$table) {" . $config['schema'] . "\n        });";

    $content = preg_replace(
        "/Schema::create\('{$table}', function \(Blueprint \$table\) \{[\s\S]*?\}\);[\s]*\}/",
        $newSchema . "\n    }",
        $content
    );

    file_put_contents($filename, $content);
    echo "Updated {$table} migration\n";
}

echo "All migrations updated!\n";
