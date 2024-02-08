module.exports = app => {
    const transactions = require("../controllers/plateformData.controller.js");
  
    // Create a new Customer
    app.post("/Ajoutransactions", transactions.create);
  
    // Retrieve all transactions
    app.get("/transactions", transactions.findAll);
  
    // Retrieve a single Customer with ID_CLIENT
    app.get("/transactions/:ID_CLIENT", transactions.findOne);
  
    // Update a Customer with ID_CLIENT
    app.put("/transactions/:ID_CLIENT", transactions.update);
  
    // Delete a Customer with ID_CLIENT
    app.delete("/transactions/:ID_CLIENT", transactions.delete);
  
    // Create a new Customer
    app.delete("/transactions", transactions.deleteAll);
  };