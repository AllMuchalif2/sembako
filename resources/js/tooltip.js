import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

/**
 * Initialize Alpine tooltip directive.
 * @param {object} Alpine Alpine instance
 */
export function initTooltip(Alpine) {
    Alpine.directive('tooltip', (el, { expression, modifiers }, { evaluateLater, effect }) => {
        let getContent = evaluateLater(expression);
        let placement = modifiers.includes('left') ? 'left' :
                        modifiers.includes('right') ? 'right' :
                        modifiers.includes('bottom') ? 'bottom' : 'top';

        let instance = tippy(el, {
            content: '',
            placement: placement,
            animation: 'scale',
        });

        effect(() => {
            getContent(content => {
                instance.setContent(content);
            });
        });
    });
}
