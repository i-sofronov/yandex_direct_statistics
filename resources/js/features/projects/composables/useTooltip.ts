import { ref } from 'vue';
import { autoPlacement, autoUpdate, flip, offset, shift, useFloating } from '@floating-ui/vue';

export function useTooltip(
    initialState = false,
    initialReference = null,
    initialFloating = null,
    tooltipId: string|null = null
) {
    const isOpen = ref(initialState);

    const reference = ref(initialReference);
    const floating = ref(initialFloating);

    const { floatingStyles } = useFloating(reference, floating, {
        whileElementsMounted: autoUpdate,
        placement: 'right',
        middleware: [
            offset(12),
            flip({
                fallbackStrategy: 'bestFit',
                fallbackAxisSideDirection: 'start',
            }),
            shift({
                padding: 10,
                boundary: 'clippingAncestors',
            }),
        ],
    })

    const _f = function(e:Event){
        const target:HTMLElement|null = e.target as HTMLElement;

        if(target?.id !== tooltipId){
            isOpen.value = false
        }
    }

    const toggle = () => {
        if(!isOpen.value){
            const tooltip = document.getElementById(tooltipId);

            if(tooltip){
                document.addEventListener('click', _f)
            }
        }
        else{
            document.removeEventListener('click', _f)
        }

        isOpen.value = !isOpen.value
    };

    return {
        isOpen,
        floatingStyles,
        toggle
    };
}
