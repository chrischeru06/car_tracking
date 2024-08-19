.flex-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 10px;
  }

  .card {
    flex: 1 1 250px;
    max-width: 250px;
    height: 110px; /* Ensures all cards have the same height */
  }

  /* Adjust the layout for smaller screens */
  @media (max-width: 1200px) {
    .card {
      flex: 1 1 calc(50% - 10px); /* 2 cards per row on medium screens */
      max-width: calc(50% - 10px);
    }
  }

  @media (max-width: 768px) {
    .card {
      flex: 1 1 100%; /* 1 card per row on small screens */
      max-width: 100%;
    }
  }
