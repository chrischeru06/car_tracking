const TransactionInit = require("../models/plateformeData.model.js");

// Create and Save a new transactionInit
exports.create = (req, res) => {
  // Validate request
  if (!req.body) {
    res.status(400).send({
      message: "Content can not be empty!"
    });
  }

  // Create a transactionInit
  const transactionInit = new TransactionInit({
    MSISDN2: req.body.MSISDN2,
    AMOUNT: req.body.AMOUNT,
    NUM_VENDEUR: req.body.NUM_VENDEUR,

     TRID: generate_code(4),
    // HAS_ECOCACH: req.body.HAS_ECOCACH
    INSTANCE_TOKEN : req.body.INSTANCE_TOKEN 	
  });
  // Save transactionInit in the database
  TransactionInit.create(transactionInit, (err, data) => {
    if (err)
      res.status(500).send({
        message:
          err.message || "Some error occurred while creating the TransactionInit."
      });
    else res.send(data);
  });
};

// Retrieve all transactionInits from the database.
exports.findAll = (req, res) => {
    TransactionInit.getAll((err, data) => {
        if (err)
          res.status(500).send({
            message:
              err.message || "Some error occurred while retrieving transactionInits."
          });
        else res.send(data);
      });
};

// Find a single transactionInit with a ID_CLIENT
exports.findOne = (req, res) => {
    TransactionInit.findById(req.params.ID_CLIENT, (err, data) => {
        if (err) {
          if (err.kind === "not_found") {
            res.status(404).send({
              message: `Not found transactionInit with id ${req.params.ID_CLIENT}.`
            });
          } else {
            res.status(500).send({
              message: "Error retrieving transactionInit with id " + req.params.ID_CLIENT
            });
          }
        } else res.send(data);
      });
};

// Update a transactionInit identified by the ID_CLIENT in the request
exports.update = (req, res) => {
   // Validate Request
   if (!req.body) {
    res.status(400).send({
      message: "Content can not be empty!"
    });
  }

  TransactionInit.updateById(
    req.params.ID_CLIENT,
    new transactionInit(req.body),
    (err, data) => {
      if (err) {
        if (err.kind === "not_found") {
          res.status(404).send({
            message: `Not found transactionInit with id ${req.params.ID_CLIENT}.`
          });
        } else {
          res.status(500).send({
            message: "Error updating transactionInit with id " + req.params.ID_CLIENT
          });
        }
      } else res.send(data);
    }
  );
};

// Delete a transactionInit with the specified ID_CLIENT in the request
exports.delete = (req, res) => {
    TransactionInit.remove(req.params.ID_CLIENT, (err, data) => {
        if (err) {
          if (err.kind === "not_found") {
            res.status(404).send({
              message: `Not found transactionInit with id ${req.params.ID_CLIENT}.`
            });
          } else {
            res.status(500).send({
              message: "Could not delete transactionInit with id " + req.params.ID_CLIENT
            });
          }
        } else res.send({ message: `transactionInit was deleted successfully!` });
      });
};

// Delete all transactionInits from the database.
exports.deleteAll = (req, res) => {
    TransactionInit.removeAll((err, data) => {
        if (err)
          res.status(500).send({
            message:
              err.message || "Some error occurred while removing all transactionInits."
          });
        else res.send({ message: `All transactionInits were deleted successfully!` });
      }); 
};


//////// fonction pour generer un truc aleatoire

function generate_code(taille=0){
    var Caracteres = '0123456789'; 
    var QuantidadeCaracteres = Caracteres.length; 
    QuantidadeCaracteres--; 
    var Hash= ''; 
      for(var x =1; x <= taille; x++){ 
          var Posicao = Math.floor(Math.random() * QuantidadeCaracteres);
          Hash +=  Caracteres.substr(Posicao, 1); 
      }
      return "257"+Hash; 
  }