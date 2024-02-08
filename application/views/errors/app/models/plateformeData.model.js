const sql = require("./db.js");

// constructeur initialisation du transaction
const TransactionInit = function(transactioninit) {
  this.MSISDN2 = transactioninit.MSISDN2;
  this.AMOUNT = transactioninit.AMOUNT;
  this.NUM_VENDEUR = transactioninit.NUM_VENDEUR;
 this.INSTANCE_TOKEN = transactioninit.INSTANCE_TOKEN ;
//   this.LANGUAGE2 = transactioninit.LANGUAGE2;
//   this.REFERENCE = transactioninit.REFERENCE;
   this.TRID = transactioninit.TRID;
//   this.HAS_ECOCACH = transactioninit.HAS_ECOCACH;
};

TransactionInit.create = (newtransaction, result) => {
  sql.query("INSERT INTO data_client SET ?", newtransaction, (err, res) => {
    if (err) {
      console.log("error: ", err);
      result(err, null);
      return;
    }
    console.log("created transaction: ", { id: res.insertId, ...newtransaction });
    result(null, { id: res.insertId, ...newtransaction });
  });
};

TransactionInit.findById = (transactionId, result) => {
  sql.query(`SELECT * FROM data_client WHERE id = ${transactionId}`, (err, res) => {
    if (err) {
      console.log("error: ", err);
      result(err, null);
      return;
    }

    if (res.length) {
      console.log("found transaction: ", res[0]);
      result(null, res[0]);
      return;
    }
     // not found transaction with the id
     result({ kind: "not_found" }, null);
    });
  };
  
  TransactionInit.getAll = result => {
    sql.query("SELECT * FROM data_client", (err, res) => {
      if (err) {
        console.log("error: ", err);
        result(null, err);
        return;
      }
  
      console.log("data_client: ", res);
      result(null, res);
    });
  };
  TransactionInit.updateById = (id, transaction, result) => {
    sql.query(
      "UPDATE data_client SET MSISDN2 = ?, AMOUNT = ?, NUM_VENDEUR = ?  WHERE id = ?",
      [TransactionInit.MSISDN2, TransactionInit.AMOUNT, TransactionInit.NUM_VENDEUR, id],
      (err, res) => {
        if (err) {
          console.log("error: ", err);
          result(null, err);
          return;
        }
  
        if (res.affectedRows == 0) {
          // not found transaction with the id
          result({ kind: "not_found" }, null);
          return;
        }
  
        console.log("updated transaction: ", { id: id, ...transaction });
        result(null, { id: id, ...transaction });
      }
    );
};

TransactionInit.remove = (id, result) => {
  sql.query("DELETE FROM data_client WHERE id = ?", id, (err, res) => {
    if (err) {
      console.log("error: ", err);
      result(null, err);
      return;
    }

    if (res.affectedRows == 0) {
      // not found transaction with the id
      result({ kind: "not_found" }, null);
      return;
    }

    console.log("deleted transaction with id: ", id);
    result(null, res);
  });
};
TransactionInit.removeAll = result => {
    sql.query("DELETE FROM data_client", (err, res) => {
      if (err) {
        console.log("error: ", err);
        result(null, err);
        return;
      }
  
      console.log(`deleted ${res.affectedRows} data_client`);
      result(null, res);
    });
  };
  
  module.exports = TransactionInit;