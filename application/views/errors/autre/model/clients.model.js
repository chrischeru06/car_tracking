const { query } = require("../functions/db")

const createClient = async (user) => {
          try {
                    return await query('INSERT INTO clients(NUMERO_COUNTRY_ID, NOM, PRENOM, NUMERO_TEL, EMAIL, MOT_DE_PASSE) VALUES(?, ?, ?, ?, ?, ?)', [
                              user.NUMERO_COUNTRY_ID,
                              user.NOM,
                              user.PRENOM,
                              user.NUMERO_TEL ?? null,
                              user.EMAIL ?? null,
                              user.MOT_DE_PASSE
                    ])
          } catch (error) {
                    throw error
          }
}
const getClient = async (login, column) => {
          try {
                    return await query("SELECT * FROM clients WHERE "+column +"= ?", [login])
          } catch (error) {
                    throw error
          }
}

module.exports = {
          createClient,
          getClient
}