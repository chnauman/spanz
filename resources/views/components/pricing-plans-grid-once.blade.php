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
    .spanz-pricing-cards-grid > .subscription-card {
        min-width: 0;
    }
</style>
@endonce
