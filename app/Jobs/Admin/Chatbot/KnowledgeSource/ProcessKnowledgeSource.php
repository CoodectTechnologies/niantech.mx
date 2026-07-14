<?php

namespace App\Jobs\Admin\Chatbot\KnowledgeSource;

use App\Enums\ChatbotKnowledgeSource\Status;
use App\Enums\ChatbotKnowledgeSource\Type;
use App\Models\ChatbotKnowledgeSource;
use App\Services\File\ExtractTextService as FileExtractTextService;
use App\Services\Scrapper\ExtractTextService as ScrapperExtractTextService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessKnowledgeSource implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $knowledgeSource;

    public function __construct(ChatbotKnowledgeSource $knowledgeSource) {
        $this->knowledgeSource = $knowledgeSource;
    }
    public function handle(): void {
        $this->markAsProcessing();
        try {
            $result = $this->extractContent();
            $this->finalizeFromResult($result);
        } catch (Throwable $e) {
            $this->failWithException($e);
        }
    }
    private function markAsProcessing(): void {
        $this->knowledgeSource->update([
            'status' => Status::PROCESSING->value,
            'status_message' => null,
        ]);
    }
    private function extractContent(): array {
        $type = Type::from($this->knowledgeSource->type);

        return match ($type) {
            Type::FILE => app(FileExtractTextService::class)->execute($this->knowledgeSource->path),
            Type::URL => app(ScrapperExtractTextService::class)->execute($this->knowledgeSource->path),
        };
    }
    private function finalizeFromResult(array $result): void {
        $isSuccess = ($result['status'] ?? null) === 'success';
        $this->knowledgeSource->update([
            'status' => $isSuccess
                ? Status::COMPLETED->value
                : Status::FAILED->value,
            'status_message' => $isSuccess
                ? null
                : ($result['message'] ?? 'Unknown error occurred during content extraction.'),
            'extracted_content' => $isSuccess
                ? ($result['content'] ?? null)
                : null,
        ]);
    }
    private function failWithException(Throwable $e) {
        $this->knowledgeSource->update([
            'status' => Status::FAILED->value,
            'status_message' => $e->getMessage(),
        ]);
    }
}
