@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('meta')
@if($page->meta_description)
<meta name="description" content="{{ $page->meta_description }}">
@endif
@if($page->meta_keywords)
<meta name="keywords" content="{{ $page->meta_keywords }}">
@endif
@endsection

@section('content')
<style>
    .page-hero {
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }

    .page-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("{{ $page->hero_image ? asset('storage/' . $page->hero_image) : '' }}") center/cover;
        opacity: 0.2;
    }

    .page-hero-content {
        position: relative;
        z-index: 1;
        color: white;
        text-align: center;
    }

    .page-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .page-hero p {
        font-size: 1.25rem;
        opacity: 0.95;
        max-width: 800px;
        margin: 0 auto;
    }

    .page-content-section {
        padding: 80px 0;
        background: #fff;
    }

    .page-content {
        max-width: 900px;
        margin: 0 auto;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }

    .page-content h2 {
        color: #872341;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .page-content h3 {
        color: #BE3144;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .page-content p {
        margin-bottom: 1.25rem;
    }

    .page-content ul, .page-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .page-content li {
        margin-bottom: 0.5rem;
    }

    .page-content blockquote {
        border-left: 4px solid #872341;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #666;
    }

    .page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 2rem 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .page-content a {
        color: #872341;
        text-decoration: underline;
    }

    .page-content a:hover {
        color: #BE3144;
    }

    .page-content code {
        background: #f5f5f5;
        padding: 2px 6px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
    }

    .page-content pre {
        background: #f5f5f5;
        padding: 1.5rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    @media (max-width: 768px) {
        .page-hero h1 {
            font-size: 2rem;
        }

        .page-hero p {
            font-size: 1rem;
        }

        .page-content-section {
            padding: 40px 0;
        }
    }
</style>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero-content">
            <h1>{{ $page->title }}</h1>
            @if($page->description)
            <p>{{ $page->description }}</p>
            @endif
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="page-content-section">
    <div class="container">
        <div class="page-content">
            {!! $page->content !!}
        </div>
    </div>
</section>
@endsection
