const { query } = require("../functions/db")
/**
 * recuperer tous les menus en fonction des filtres
 * @param {Number} categoryId l'id de la categorie
 * @param {Number} restoId l'id du erstaurant
 * @param {String} q la recherche
 * @param {Number} offset l'offset
 * @param {Number} limit la limit
 * @returns Promise
 */
const getAll = async (categoryId, restoId, q, offset = 0, limit = 10) => {
          try {
                    var binds = []
                    var sqlQuery = "SELECT mr.ID_MENU_RESTAURANT, mr.PRIX, "
                    sqlQuery += "menu.ID_MENU, menu.NOM_MENU, menu.DESCRIPTION, menu.IMAGE_1, menu.IMAGE_2, menu.IMAGE_3, "
                    sqlQuery += "resto.ID_RESTAURANT, resto.NOM_RESTAURANT, resto.IMAGE IMAGE_RESTAURANT, "
                    sqlQuery += "mca.ID_CATEGORIE, mca.NOM_CATEGORIE, "
                    sqlQuery += "SUM(com.QUANTITE) COMMANDES "
                    sqlQuery += "FROM menu_restaurants mr "
                    sqlQuery += "LEFT JOIN menu ON menu.ID_MENU = mr.ID_MENU "
                    sqlQuery += "LEFT JOIN restaurants resto ON resto.ID_RESTAURANT = mr.ID_RESTAURANT "
                    sqlQuery += "LEFT JOIN menu_categories mca ON mca.ID_CATEGORIE = menu.ID_CATEGORIE "
                    sqlQuery += "LEFT JOIN commande_details com ON mr.ID_MENU_RESTAURANT = com.ID_MENU_RESTO WHERE 1 "
                    if(categoryId) {
                              sqlQuery += "AND menu.ID_CATEGORIE = ? "
                              binds.push(categoryId)
                    }
                    if(restoId) {
                              sqlQuery += "AND mr.ID_RESTAURANT = ? "
                              binds.push(restoId)
                    }
                    if(q && q != '') {
                              sqlQuery += "AND (menu.NOM_MENU LIKE ? OR resto.NOM_RESTAURANT LIKE ? OR mca.NOM_CATEGORIE LIKE ?) "
                              binds.push(`%${q}%`, `%${q}%`, `%${q}%`)
                    }
                    sqlQuery += "GROUP BY mr.ID_MENU_RESTAURANT "
                    sqlQuery += "ORDER BY COMMANDES DESC "
                    sqlQuery += `LIMIT ${offset}, ${limit}`
                    return await query(sqlQuery, binds)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const getMenu = async (menuRestoId) => {
          try {
                    var binds = [menuRestoId]
                    var sqlQuery = "SELECT mr.ID_MENU_RESTAURANT, mr.PRIX, "
                    sqlQuery += "menu.ID_MENU, menu.NOM_MENU, menu.DESCRIPTION, menu.IMAGE_1, menu.IMAGE_2, menu.IMAGE_3, "
                    sqlQuery += "resto.ID_RESTAURANT, resto.NOM_RESTAURANT, resto.IMAGE IMAGE_RESTAURANT, "
                    sqlQuery += "mca.ID_CATEGORIE, mca.NOM_CATEGORIE "
                    sqlQuery += "FROM menu_restaurants mr "
                    sqlQuery += "LEFT JOIN menu ON menu.ID_MENU = mr.ID_MENU "
                    sqlQuery += "LEFT JOIN restaurants resto ON resto.ID_RESTAURANT = mr.ID_RESTAURANT "
                    sqlQuery += "LEFT JOIN menu_categories mca ON mca.ID_CATEGORIE = menu.ID_CATEGORIE WHERE ID_MENU_RESTAURANT  = ?"
                    return query(sqlQuery, binds)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getAll,
          getMenu
}