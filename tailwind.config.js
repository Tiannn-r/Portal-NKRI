/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: 'var(--color-primary, #B91C1C)',
                    hover: 'var(--color-primary-hover, #991B1B)',
                    soft: 'var(--color-primary-soft, #FEF2F2)',
                    ring: 'var(--color-primary-ring, #FCA5A5)',
                },
                surface: {
                    DEFAULT: 'var(--color-surface, #FAFAFA)',
                },
                border: {
                    DEFAULT: 'var(--color-border, #E5E7EB)',
                },
                content: {
                    DEFAULT: 'var(--color-text, #111827)',
                    muted: 'var(--color-text-muted, #4B5563)',
                    subtle: 'var(--color-text-subtle, #6B7280)',
                },
            },
            fontFamily: {
                sans: [
                    'Plus Jakarta Sans',
                    'system-ui',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"Segoe UI"',
                    'Roboto',
                    'sans-serif',
                ],
            },
            maxWidth: {
                container: '1200px',
            },
            boxShadow: {
                card: '0 1px 2px 0 rgba(0, 0, 0, 0.03)',
                'card-hover': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05)',
            },
        },
    },
    plugins: [],
};
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: 'var(--color-primary, #B91C1C)',
                    hover: 'var(--color-primary-hover, #991B1B)',
                    soft: 'var(--color-primary-soft, #FEF2F2)',
                    ring: 'var(--color-primary-ring, #FCA5A5)',
                },
                surface: {
                    DEFAULT: 'var(--color-surface, #FAFAFA)',
                },
                border: {
                    DEFAULT: 'var(--color-border, #E5E7EB)',
                },
                content: {
                    DEFAULT: 'var(--color-text, #111827)',
                    muted: 'var(--color-text-muted, #4B5563)',
                    subtle: 'var(--color-text-subtle, #6B7280)',
                },
            },
            fontFamily: {
                sans: [
                    'Plus Jakarta Sans',
                    'system-ui',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"Segoe UI"',
                    'Roboto',
                    'sans-serif',
                ],
            },
            maxWidth: {
                container: '1200px',
            },
            boxShadow: {
                card: '0 1px 2px 0 rgba(0, 0, 0, 0.03)',
                'card-hover': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05)',
            },
        },
    },
    plugins: [],
};

