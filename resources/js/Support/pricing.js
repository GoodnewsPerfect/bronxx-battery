/**
 * Single source of truth for Espees price/quantity math on the frontend.
 * Authoritative totals for charging always come from the backend
 * (cart/order totals); these helpers are for display only.
 */

export function toAmount(value) {
    const amount = Number(value);
    return Number.isFinite(amount) ? amount : 0;
}

export function calculateTotal(price, quantity) {
    return toAmount(price) * Math.max(1, Number(quantity) || 1);
}

export function formatEspees(amount) {
    return toAmount(amount).toFixed(2);
}
