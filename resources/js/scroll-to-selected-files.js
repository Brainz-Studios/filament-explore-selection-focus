(() => {
    const config = window.filamentExploreSelectionFocus ?? {
        scrollBehavior: 'smooth',
        scrollBlock: 'center',
    }

    const debounceMs = 120
    let debounceTimer = null
    let observedModal = null
    let observer = null

    function findExploreModal() {
        return document.querySelector('.fi-modal-window:not([inert])')
            ?? document.querySelector('.fi-modal:not([inert])')
    }

    function findSelectedFileElement(filesContainer) {
        if (! filesContainer) {
            return null
        }

        const checkedCheckbox = filesContainer.querySelector('[data-file-key-hash] input.fi-checkbox-input:checked')

        if (checkedCheckbox) {
            return checkedCheckbox.closest('[data-file-key-hash]')
        }

        const ringSelected = filesContainer.querySelector('[data-file-key-hash] .ring-primary-600')

        if (ringSelected) {
            return ringSelected.closest('[data-file-key-hash]')
        }

        const rowSelected = filesContainer.querySelector('[data-file-key-hash].border-l-primary-600')

        if (rowSelected) {
            return rowSelected
        }

        const highlighted = filesContainer.querySelector('[data-file-key-hash].\\!bg-gray-50, [data-file-key-hash].dark\\:\\!bg-gray-800')

        if (highlighted) {
            return highlighted
        }

        return null
    }

    function scrollToSelectedExploreFiles() {
        const modal = findExploreModal()

        if (! modal) {
            return
        }

        const filesContainers = modal.querySelectorAll('[x-ref="files"]')

        if (filesContainers.length === 0) {
            return
        }

        for (const filesContainer of filesContainers) {
            const selectedElement = findSelectedFileElement(filesContainer)

            if (! selectedElement) {
                continue
            }

            selectedElement.scrollIntoView({
                behavior: config.scrollBehavior,
                block: config.scrollBlock,
                inline: 'nearest',
            })

            return
        }
    }

    function scheduleScroll() {
        clearTimeout(debounceTimer)

        debounceTimer = setTimeout(() => {
            requestAnimationFrame(scrollToSelectedExploreFiles)
        }, debounceMs)
    }

    function startObservingModal() {
        const modal = findExploreModal()

        if (! modal || modal === observedModal) {
            return
        }

        if (observer) {
            observer.disconnect()
        }

        observedModal = modal

        observer = new MutationObserver(() => {
            scheduleScroll()
        })

        observer.observe(modal, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'style', 'checked'],
        })

        scheduleScroll()
    }

    function stopObservingModal() {
        if (observer) {
            observer.disconnect()
            observer = null
        }

        observedModal = null
    }

    document.addEventListener('click', (event) => {
        if (event.target.closest('[wire\\:click*="mountAction"], [wire\\:click*="callMountedAction"], .fi-ac-btn-action')) {
            setTimeout(startObservingModal, 50)
        }
    }, true)

    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', () => {
            if (findExploreModal()) {
                startObservingModal()
            } else {
                stopObservingModal()
            }
        })
    })

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            stopObservingModal()
        }
    })
})()
