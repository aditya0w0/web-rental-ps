<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\ArticleReaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleEngagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_comment_is_pending_and_remembers_identity(): void
    {
        $article = $this->publishedArticle();

        $response = $this->post(route('articles.comments.store', $article), [
            'guest_name' => 'Raka Guest',
            'guest_email' => 'raka@example.com',
            'body' => 'Masih ada stok PS5 buat weekend?',
        ]);

        $response
            ->assertRedirect(route('articles.show', $article->slug))
            ->assertSessionHas('success')
            ->assertCookie('article_guest_name')
            ->assertCookie('article_guest_email');

        $this->assertDatabaseHas('article_comments', [
            'article_id' => $article->id,
            'user_id' => null,
            'guest_name' => 'Raka Guest',
            'guest_email' => 'raka@example.com',
            'body' => 'Masih ada stok PS5 buat weekend?',
            'status' => ArticleComment::STATUS_PENDING,
        ]);

        $this->get(route('articles.show', $article->slug))
            ->assertOk()
            ->assertDontSee('Masih ada stok PS5 buat weekend?');
    }

    public function test_authenticated_comment_is_approved_and_visible(): void
    {
        $article = $this->publishedArticle();
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->post(route('articles.comments.store', $article), [
            'body' => 'Artikel ini helpful banget buat milih paket.',
        ])->assertRedirect(route('articles.show', $article->slug));

        $this->assertDatabaseHas('article_comments', [
            'article_id' => $article->id,
            'user_id' => $user->id,
            'status' => ArticleComment::STATUS_APPROVED,
            'body' => 'Artikel ini helpful banget buat milih paket.',
        ]);

        $this->get(route('articles.show', $article->slug))
            ->assertOk()
            ->assertSee('Artikel ini helpful banget buat milih paket.');
    }

    public function test_guest_comment_form_keeps_saved_identity_as_fields(): void
    {
        $article = $this->publishedArticle();

        $this->withCookie('article_guest_name', 'Raka Guest')
            ->withCookie('article_guest_email', 'raka@example.com')
            ->get(route('articles.show', $article->slug))
            ->assertOk()
            ->assertSee('value="Raka Guest"', false)
            ->assertSee('value="raka@example.com"', false)
            ->assertSee('Tulis komentar')
            ->assertDontSee('Komentar sebagai')
            ->assertDontSee('Guest boleh komentar');
    }

    public function test_guest_reaction_uses_saved_token_and_can_be_toggled(): void
    {
        $article = $this->publishedArticle();

        $response = $this->post(route('articles.reactions.toggle', $article), [
            'type' => ArticleReaction::TYPE_HELPFUL,
        ]);

        $response
            ->assertRedirect(route('articles.show', $article->slug))
            ->assertCookie('article_guest_token');

        $reaction = ArticleReaction::query()->where('article_id', $article->id)->first();
        $this->assertNotNull($reaction);
        $this->assertSame(ArticleReaction::TYPE_HELPFUL, $reaction->type);
        $this->assertNotNull($reaction->guest_token_hash);

        $this->withCookie('article_guest_token', 'same-browser-token')
            ->post(route('articles.reactions.toggle', $article), [
                'type' => ArticleReaction::TYPE_LIKE,
            ]);

        $this->assertDatabaseHas('article_reactions', [
            'article_id' => $article->id,
            'type' => ArticleReaction::TYPE_LIKE,
            'guest_token_hash' => hash('sha256', 'same-browser-token'),
        ]);

        $this->withCookie('article_guest_token', 'same-browser-token')
            ->post(route('articles.reactions.toggle', $article), [
                'type' => ArticleReaction::TYPE_LIKE,
            ]);

        $this->assertDatabaseMissing('article_reactions', [
            'article_id' => $article->id,
            'type' => ArticleReaction::TYPE_LIKE,
            'guest_token_hash' => hash('sha256', 'same-browser-token'),
        ]);
    }

    public function test_admin_delete_marks_comment_and_keeps_trace(): void
    {
        $article = $this->publishedArticle();
        $admin = User::factory()->create(['role' => 'admin']);
        $comment = ArticleComment::create([
            'article_id' => $article->id,
            'guest_name' => 'Guest Baru',
            'guest_email' => 'guest@example.com',
            'body' => 'Komentar yang perlu dihapus.',
            'status' => ArticleComment::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $this->actingAs($admin)->patch(route('admin.article-comments.destroy-trace', $comment), [
            'moderation_note' => 'Spam promo di artikel.',
        ])->assertRedirect(route('admin.article-comments.index'));

        $comment->refresh();

        $this->assertSame(ArticleComment::STATUS_DELETED, $comment->status);
        $this->assertSame($admin->id, $comment->moderated_by);
        $this->assertNotNull($comment->moderated_at);
        $this->assertSame('Spam promo di artikel.', $comment->moderation_note);
        $this->assertSame('Komentar yang perlu dihapus.', $comment->body);
    }

    public function test_admin_moderation_page_renders_comment_queue(): void
    {
        $article = $this->publishedArticle();
        $admin = User::factory()->create(['role' => 'admin']);

        ArticleComment::create([
            'article_id' => $article->id,
            'guest_name' => 'Guest Queue',
            'guest_email' => 'queue@example.com',
            'body' => 'Komentar pending di queue.',
            'status' => ArticleComment::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.article-comments.index'))
            ->assertOk()
            ->assertSee('Komentar artikel')
            ->assertSee('Komentar pending di queue.')
            ->assertSee('Jejak admin');
    }
    private function publishedArticle(): Article
    {
        return Article::create([
            'title' => 'Panduan Rental PS5',
            'slug' => 'panduan-rental-ps5',
            'excerpt' => 'Tips rental PS5 buat acara rumah.',
            'body' => "Paragraf pertama artikel.\n\nParagraf kedua artikel.",
            'author_name' => 'Tim PlayHub',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
