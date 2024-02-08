const { query } = require("../functions/db")
const getAll = async (commandeId, offset = 0, limit = 30) => {
          try {
                    var sqlQuery = "SELECT * FROM commande_messages message "
                    sqlQuery += "LEFT JOIN restaurants resto ON resto.ID_RESTAURANT = message.ID_RESTO "
                    sqlQuery += "WHERE message.ID_COMMANDE = ? "
                    sqlQuery += "ORDER BY message.DATE_MESSAGE DESC "
                    sqlQuery += `LIMIT ${offset}, ${limit}`
                    return await query(sqlQuery, [commandeId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const createMessage = async (commandeId, restoId, contenu, idStatus) => {
          try {
                    var binds = [commandeId, restoId, contenu, idStatus]
                    var sqlQuery = "INSERT INTO commande_messages(ID_COMMANDE, ID_RESTO, CONTENU, ID_STATUS_COMMANDE) VALUES (?, ?, ?, ?)"
                    return await query(sqlQuery, binds)
          } catch (error){
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getAll,
          createMessage
}