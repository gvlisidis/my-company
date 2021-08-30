<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_of_ideas_shows_on_main_page()
    {

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $idea1 = Idea::factory()->create([
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'description' => 'Description of my first Idea',
        ]);

        $idea2 = Idea::factory()->create([
            'title' => 'My Second Idea',
            'category_id' => $categoryTwo->id,
            'description' => 'Description of my second Idea',
        ]);
        $response = $this->get(route('ideas.index'));

        $response->assertSuccessful();
        $response->assertSee($idea1->title);
        $response->assertSee($idea1->description);
        $response->assertSee($categoryOne->name);
        $response->assertSee($idea2->title);
        $response->assertSee($idea2->description);
        $response->assertSee($categoryTwo->name);
    }

    /** @test */
    public function single_idea_shows_correctly_on_the_show_page()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'description' => 'Description of my first Idea',
        ]);

        $response = $this->get(route('ideas.show', $idea));

        $response->assertSuccessful();
        $response->assertSee($idea->title);
        $response->assertSee($idea->description);
        $response->assertSee($categoryOne->name);
    }

    /** @test */
    public function ideas_pagination_works()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        Idea::factory(Idea::PAGINATION_COUNT + 1)->create([
            'category_id' => $categoryOne->id,
        ]);

        $ideaOne = Idea::find(1);
        $ideaOne->title = 'My First Idea';
        $ideaOne->save();

        $ideaEleven = Idea::find(11);
        $ideaEleven->title = 'My Eleventh Idea';
        $ideaEleven->save();
        //dd($ideaEleven);

        $response =$this->get(route('ideas.index'));

        $response->assertSee($ideaOne->title);
        $response->assertDontSee($ideaEleven->title);

        $response = $this->get('/ideas?page=2');

        $response->assertSee($ideaEleven->title);
        $response->assertDontSee($ideaOne->title);
    }

    /** @test */
    public function same_idea_title_different_slugs()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $ideaOne = Idea::factory()->create([
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'description' => 'Description for my first idea',
        ]);

        $ideaTwo = Idea::factory()->create([
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'description' => 'Another Description for my first idea',
        ]);

        $response = $this->get(route('ideas.show', $ideaOne));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea');

        $response = $this->get(route('ideas.show', $ideaTwo));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea-2');
    }


}
