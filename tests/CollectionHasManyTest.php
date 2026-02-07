<?php

namespace Victorstack\LaravelCollectionHasMany\Tests;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Victorstack\LaravelCollectionHasMany\CollectionHasMany;

class CollectionHasManyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        CollectionHasMany::register();
    }

    public function test_has_many_with_arrays()
    {
        $users = new Collection([
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
        ]);

        $posts = new Collection([
            ['id' => 101, 'user_id' => 1, 'title' => 'Post A'],
            ['id' => 102, 'user_id' => 1, 'title' => 'Post B'],
            ['id' => 103, 'user_id' => 2, 'title' => 'Post C'],
        ]);

        $results = $users->hasMany($posts, 'user_id', 'id', 'posts');

        $this->assertCount(2, $results);
        
        $alice = $results->firstWhere('id', 1);
        $this->assertArrayHasKey('posts', $alice);
        $this->assertCount(2, $alice['posts']);
        $this->assertEquals('Post A', $alice['posts'][0]['title']);

        $bob = $results->firstWhere('id', 2);
        $this->assertCount(1, $bob['posts']);
        $this->assertEquals('Post C', $bob['posts'][0]['title']);
    }

    public function test_has_many_with_objects()
    {
        $users = new Collection([
            (object) ['id' => 1, 'name' => 'Alice'],
            (object) ['id' => 2, 'name' => 'Bob'],
        ]);

        $posts = [
            (object) ['id' => 101, 'user_id' => 1, 'title' => 'Post A'],
            (object) ['id' => 102, 'user_id' => 1, 'title' => 'Post B'],
        ];

        // Pass array instead of collection for second arg to test auto-conversion
        $results = $users->hasMany($posts, 'user_id', 'id', 'user_posts');

        $alice = $results->firstWhere('id', 1);
        $this->assertTrue(isset($alice->user_posts));
        $this->assertCount(2, $alice->user_posts);

        $bob = $results->firstWhere('id', 2);
        $this->assertTrue(isset($bob->user_posts));
        $this->assertCount(0, $bob->user_posts); // Bob has no posts
    }
}
