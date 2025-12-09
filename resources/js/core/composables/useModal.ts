import { ref, computed } from 'vue';

export interface ModalOptions {
    title?: string;
    size?: 'sm' | 'md' | 'lg' | 'xl';
    closable?: boolean;
    overlay?: boolean;
    closeOnOverlay?: boolean;
    showHeader?: boolean;
    showFooter?: boolean;
}

export function useModal(initialState = false) {
    const isOpen = ref(initialState);

    const open = () => {
        isOpen.value = true;
        document.body.style.overflow = 'hidden';
    };

    const close = () => {
        isOpen.value = false;
        document.body.style.overflow = '';
    };

    const toggle = () => {
        isOpen.value ? close() : open();
    };

    return {
        isOpen,
        open,
        close,
        toggle
    };
}
