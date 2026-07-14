import React from 'react'
import { createRoot } from 'react-dom/client'
import { sileo, Toaster } from 'sileo'

const isDarkTheme = () =>
    (document.documentElement.getAttribute('data-bs-theme') ?? '') === 'dark'

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('sileo-portal')

    if (!root) return

    const position = root.dataset.position ?? 'top-right'

    const reactRoot = createRoot(root)

    const renderToaster = () => {
        reactRoot.render(
            React.createElement(Toaster, {
                key: isDarkTheme() ? 'dark' : 'light',
                position,
                options: {
                    fill: isDarkTheme() ? '#ffffff' : '#171717',
                    roundness: 16,
                },
            })
        )
    }

    renderToaster()

    // ── Detectar cambios de tema ────────────────────────────────

    const observer = new MutationObserver(() => {
        renderToaster()
    })

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-bs-theme'],
    })

    // ── Basic toasts ────────────────────────────────────────────

    window.addEventListener('sileo', ({ detail }) => {
        const {
            type = 'info',
            title,
            description,
            duration,
            position,
            action,
        } = detail ?? {}

        const opts = {
            ...(title && { title }),
            ...(description && { description }),
            ...(duration && { duration }),
            ...(position && { position }),

            ...(action && {
                action: {
                    label: action.label,
                    onClick: () => Livewire.dispatch(
                        action.event,
                        ...(action.params ?? [])
                    ),
                },
            }),
        }

        const map = {
            success: () => sileo.success(opts),
            error: () => sileo.error(opts),
            warning: () => sileo.warning(opts),
            loading: () => sileo.loading(opts),
            info: () => sileo.info(opts),
        }

        ;(map[type] ?? map.info)()
    })

    // ── Promise toasts ──────────────────────────────────────────

    window.addEventListener('sileo.promise', ({ detail }) => {
        const {
            event,
            loading = 'Loading...',
            success = 'Done!',
            error = 'Failed.',
        } = detail ?? {}

        const promise = new Promise((resolve, reject) => {
            window.addEventListener(
                `sileo.resolve.${event}`,
                resolve,
                { once: true }
            )

            window.addEventListener(
                `sileo.reject.${event}`,
                reject,
                { once: true }
            )

            Livewire.dispatch(event)
        })

        sileo.promise(promise, {
            loading,
            success,
            error,
        })
    })
})