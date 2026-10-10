<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Prepares a case study's custom HTML block for rendering.
 *
 * Uploaded assets are referenced in code as [[asset:original-name.ext]] and resolved to public URLs.
 * "Isolated" blocks are wrapped in a standalone document for a sandboxed iframe (srcdoc);
 * "inline" blocks render straight into the page with their CSS confined via @scope.
 */
class HtmlEmbed
{
    public const MODE_ISOLATED = 'isolated';

    public const MODE_INLINE = 'inline';

    private const TOKEN_PATTERN = '/\[\[asset:\s*([^\]]+?)\s*\]\]/';

    /**
     * @param  array{assets?: array<int|string, string>|null, asset_names?: array<string, string>|null}  $block
     * @return array<string, string> original file name => public URL
     */
    public static function assetMap(array $block): array
    {
        $names = $block['asset_names'] ?? [];

        return collect($block['assets'] ?? [])
            ->values()
            ->mapWithKeys(fn (string $path): array => [
                $names[$path] ?? basename($path) => Storage::disk('public')->url($path),
            ])
            ->all();
    }

    /**
     * Replaces [[asset:name]] tokens with URLs. Unknown names are left as-is so mistakes are visible.
     *
     * @param  array<string, string>  $assets
     */
    public static function resolveAssets(string $code, array $assets): string
    {
        return preg_replace_callback(
            self::TOKEN_PATTERN,
            fn (array $match): string => $assets[$match[1]] ?? $match[0],
            $code,
        ) ?? $code;
    }

    /**
     * @param  array{html?: string|null, css?: string|null, js?: string|null, assets?: array<int|string, string>|null, asset_names?: array<string, string>|null}  $block
     * @return array{html: string, css: string, js: string}
     */
    public static function resolve(array $block): array
    {
        $assets = self::assetMap($block);

        return [
            'html' => self::resolveAssets((string) ($block['html'] ?? ''), $assets),
            // Prevent a stray closing tag in the code from ending the <style>/<script> element early.
            'css' => str_ireplace('</style', '<\/style', self::resolveAssets((string) ($block['css'] ?? ''), $assets)),
            'js' => str_ireplace('</script', '<\/script', self::resolveAssets((string) ($block['js'] ?? ''), $assets)),
        ];
    }

    /**
     * Full HTML document for an isolated block's sandboxed iframe. Reports its height to the parent page.
     *
     * @param  array{html?: string|null, css?: string|null, js?: string|null, assets?: array<int|string, string>|null, asset_names?: array<string, string>|null}  $block
     */
    public static function document(array $block): string
    {
        $code = self::resolve($block);

        return <<<HTML
        <!doctype html>
        <html lang="en">
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base target="_blank">
        <style>
        *,*::before,*::after{box-sizing:border-box}
        html,body{margin:0;padding:0;background:transparent;color:#0a0a0a;font-family:ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}
        img,video,canvas,svg{max-width:100%;height:auto;display:block}
        </style>
        <style>{$code['css']}</style>
        </head>
        <body>
        {$code['html']}
        <script>{$code['js']}</script>
        <script>
        (function () {
            var last = 0;
            function report() {
                var height = Math.ceil(document.documentElement.getBoundingClientRect().height);
                if (height !== last) {
                    last = height;
                    parent.postMessage({ type: 'tod-embed:height', height: height }, '*');
                }
            }
            new ResizeObserver(report).observe(document.documentElement);
            window.addEventListener('load', report);
            report();
        })();
        </script>
        </body>
        </html>
        HTML;
    }

    /** Confines inline-mode CSS to the block's wrapper so it cannot restyle the rest of the site. */
    public static function scopedCss(string $css, string $scopeId): string
    {
        return trim($css) === '' ? '' : "@scope ([data-embed=\"{$scopeId}\"]) {\n{$css}\n}";
    }
}
