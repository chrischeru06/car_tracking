const { query } = require("../functions/db")
/**
 * recuperer tous les commandes en fonction des filtres
 * 
 * @param {String} q la recherche
 * @param {Number} offset l'offset
 * @param {Number} limit la limit
 * @returns Promise
 */
const getAll = async (clientId, q, offset = 0, limit = 10) => {
          try {
                    var binds = [clientId]
                    var sqlQuery = "SELECT co.ID_COMMANDE, co.DATE_COMMANDE, co.ID_STATUS_COMMANDE, co.NOM_LIVRAISON, co.PRENOM_LIVRAISON, co.NUMERO_LIVRAISON, co.POSITION_STATUS, co.ADRESSE_LIVRAISON, co.MONTANT_TOTAL, (SELECT COUNT(ID_COMMANDE) FROM commande_details cd WHERE ID_COMMANDE = co.ID_COMMANDE) COMMANDES, "
                    sqlQuery += "(SELECT menu.IMAGE_1 FROM commande_details cd JOIN menu_restaurants mr ON mr.ID_MENU_RESTAURANT = cd.ID_MENU_RESTO JOIN menu ON menu.ID_MENU =mr.ID_MENU WHERE ID_COMMANDE = co.ID_COMMANDE LIMIT 1) IMAGE "
                    sqlQuery += "FROM commandes co "
                    sqlQuery += "WHERE 1 AND co.ID_CLIENT = ? AND co.ID_COMMANDE IN (SELECT ID_COMMANDE FROM commande_details GROUP BY ID_COMMANDE) "
                    sqlQuery += "ORDER BY co.DATE_COMMANDE DESC "
                    sqlQuery += `LIMIT ${offset}, ${limit}`
                    return await query(sqlQuery, binds)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const getCommandeDetail = async (commandeId, q, offset = 0, limit = 10) => {
          try {
                    var binds = [commandeId]
                    var sqlQuery = "SELECT cd.QUANTITE, cd.PRIX_TOTAL, "
                    sqlQuery += "mr.ID_MENU_RESTAURANT, mr.PRIX, "
                    sqlQuery += "menu.ID_MENU, menu.NOM_MENU, menu.DESCRIPTION, menu.IMAGE_1, menu.IMAGE_2, menu.IMAGE_3, "
                    sqlQuery += "resto.ID_RESTAURANT, resto.NOM_RESTAURANT, resto.IMAGE IMAGE_RESTAURANT "
                    sqlQuery += "FROM commande_details cd "
                    sqlQuery += "LEFT JOIN menu_restaurants mr ON mr.ID_MENU_RESTAURANT = cd.ID_MENU_RESTO "
                    sqlQuery += "LEFT JOIN menu ON menu.ID_MENU = mr.ID_MENU "
                    sqlQuery += "LEFT JOIN restaurants resto ON resto.ID_RESTAURANT = mr.ID_RESTAURANT "
                    sqlQuery += "WHERE 1 AND cd.ID_COMMANDE = ? "
                    if(q && q != '') {
                              sqlQuery += "AND (menu.NOM_MENU LIKE ? OR resto.NOM_RESTAURANT LIKE ?) "
                              binds.push(`%${q}%`, `%${q}%`)
                    }
                    sqlQuery += `LIMIT ${offset}, ${limit}`
                    return await query(sqlQuery, binds)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const create = async (data) => {
          try {
                    return await query('INSERT INTO commandes(ID_CLIENT, NOM_LIVRAISON, PRENOM_LIVRAISON, NUMERO_LIVRAISON, POSITION_STATUS, PROVINCE_ID_LIVRAISON, COMMUNE_ID_LIVRAISON, ADRESSE_LIVRAISON, LONGITUDE_LIVRAISON, LATITUDE_LIVRAISON, LATITUDE_CLIENT, LONGITUDE_CLIENT, MONTANT_COMMANDE, MONTANT_TRANSPORT, MONTANT_SERVICE, MONTANT_TOTAL) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', data)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const createCommandeDetail = async (detail) => {
          try {
                    return await query('INSERT INTO commande_details(ID_COMMANDE, ID_MENU_RESTO, PRIX_UNITAIRE, QUANTITE, PRIX_TOTAL) VALUES(?, ?, ?, ?, ?)', detail)
          } catch (error) {
                    console.log(error)
                    throw error         
          }
}

module.exports ={
          getAll,
          getCommandeDetail,
          create,
          createCommandeDetail
}