@once
<style>
    /* Ensures pricing cards stay in a proper row/column grid when Tailwind utilities are missing on production */
    .spanz-pricing-cards-grid {
        display: grid !important;
        width: 100%;
        grid-template-columns: 1fr;
        gap: 1rem;
        align-items: stretch;
    }
    @media (min-width: 768px) {
        .spanz-pricing-cards-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 1280px) {
        .spanz-pricing-cards-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    .spanz-pricing-cards-grid > .subscription-card,
    #subscriptionModal .subscription-card {
        min-width: 0;
        display: flex;
        flex-direction: column;
    }
    .spanz-pricing-cards-grid > .subscription-card > .spanz-plan-card-face,
    #subscriptionModal .subscription-card > .spanz-plan-card-face {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: visible;
    }
    .spanz-plan-card-stack {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }
    .spanz-plan-card-body {
        flex: 1 1 auto;
        min-height: 0;
    }
    .spanz-plan-card-actions {
        flex-shrink: 0;
        margin-top: auto;
        padding-top: 0.5rem;
    }
    .spanz-plan-card-title {
        line-height: 1.2;
        overflow: visible;
        word-break: normal;
    }
    /* Spacer when a plan has no feature bullets (e.g. Buyer) so row heights stay even */
    .spanz-plan-card-features-spacer {
        min-height: 5.5rem;
    }
    /* Selected plan highlight — avoids Tailwind arbitrary bg classes missing on production */
    .spanz-pricing-cards-grid .subscription-card button.spanz-plan-selectable.is-selected,
    #subscriptionModal .subscription-card button.spanz-plan-selectable.is-selected {
        background-color: #eab308 !important;
        color: #ffffff !important;
        border-color: transparent !important;
    }
</style>
@endonce
