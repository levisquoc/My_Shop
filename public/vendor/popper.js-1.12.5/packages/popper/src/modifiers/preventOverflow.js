import getOffsetParent from '../utils/getOffsetParent';
import getBoundaries from '../utils/getBoundaries';

/**
 * @function
 * @memberof Modifiers
 * @argument {Object} data - The data object generated by `update` method
 * @argument {Object} options - Modifiers configuration and options
 * @returns {Object} The data object, properly modified
 */
export default function preventOverflow(data, options) {
    let boundariesElement =
        options.boundariesElement || getOffsetParent(data.instance.popper);

    // If offsetParent is the reference element, we really want to
    // go one step up and use the next offsetParent as reference to
    // avoid to make this modifier completely useless and look like broken
    if (data.instance.reference === boundariesElement) {
        boundariesElement = getOffsetParent(boundariesElement);
    }

    const boundaries = getBoundaries(
        data.instance.popper,
        data.instance.reference,
        options.padding,
        boundariesElement
    );
    options.boundaries = boundaries;

    const order = options.priority;
    let popper = data.offsets.popper;

    const check = {
        primary(placement) {
            let value = popper[placement];
            if (
                popper[placement] < boundaries[placement] &&
                !options.escapeWithReference
            ) {
                value = Math.max(popper[placement], boundaries[placement]);
            }
            return {[placement]: value};
        },
        secondary(placement) {
            const mainSide = placement === 'right' ? 'left' : 'top';
            let value = popper[mainSide];
            if (
                popper[placement] > boundaries[placement] &&
                !options.escapeWithReference
            ) {
                value = Math.min(
                    popper[mainSide],
                    boundaries[placement] -
                    (placement === 'right' ? popper.width : popper.height)
                );
            }
            return {[mainSide]: value};
        },
    };

    order.forEach(placement = > {
        const side = ['left', 'top'].indexOf(placement) !== -1
            ? 'primary'
            : 'secondary';
    popper = {...popper,
...
    check[side](placement)
}
    ;
})
    ;

    data.offsets.popper = popper;

    return data;
}
