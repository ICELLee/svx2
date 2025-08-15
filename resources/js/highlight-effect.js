function initHighlightEffect() {
    document.querySelectorAll('.highlight-effect').forEach((el) => {
        const onMove = (e) => {
            const rect = el.getBoundingClientRect()
            el.style.setProperty('--mouse-x', `${e.clientX - rect.left}px`)
            el.style.setProperty('--mouse-y', `${e.clientY - rect.top}px`)
        }
        el.addEventListener('mousemove', onMove)
        el.addEventListener('mouseleave', () => {
            el.style.removeProperty('--mouse-x')
            el.style.removeProperty('--mouse-y')
        })
    })
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHighlightEffect)
} else {
    initHighlightEffect()
}
