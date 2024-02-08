const { query } = require("../functions/db")
const getMenus = async (clientId, offset = 0, limit = 10) => {
          try {
                    var binds = [clientId]
                    var sqlQuery = "SELECT mv.ID_FAVORI, "
                    sqlQuery += "menu.ID_MENU, menu.NOM_MENU, menu.DESCRIPTION, menu.IMAGE_1, menu.IMAGE_2, menu.IMAGE_3, "
                    sqlQuery += "resto.ID_RESTAURANT, resto.NOM_RESTAURANT, resto.IMAGE IMAGE_RESTAURANT, "
                    sqlQuery += "mr.ID_MENU_RESTAURANT, mr.PRIX "
                    sqlQuery += "FROM clients_menu_favoris mv "
                    sqlQuery += "LEFT JOIN menu_restaurants mr ON mr.ID_MENU_RESTAURANT = mv.ID_MENU_RESTO "
                    sqlQuery += "LEFT JOIN menu ON menu.ID_MENU = mr.ID_MENU "
                    sqlQuery += "LEFT JOIN restaurants resto ON resto.ID_RESTAURANT = mr.ID_RESTAURANT WHERE CLIENT_ID = ? "
                    sqlQuery += "ORDER BY DATE_INSERTION DESC "
                    sqlQuery += `LIMIT ${offset}, ${limit}`
                    return await query(sqlQuery, binds)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const createFavoriteMenu = async (clientId, menuRestoId) => {
          try {
                    return query('INSERT INTO clients_menu_favoris(ID_MENU_RESTO, CLIENT_ID) VALUES(?, ?) ', [menuRestoId, clientId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const deleteFavoriteMenu = async (clientId, menuRestoId) => {
          try {
                    return query('DELETE FROM clients_menu_favoris WHERE ID_MENU_RESTO = ? AND CLIENT_ID = ?', [menuRestoId, clientId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}


const createFavoriteResto = async (clientId, restoId) => {
          try {
                    return query('INSERT INTO clients_resto_favoris(ID_RESTO, ID_CLIENT) VALUES(?, ?) ', [restoId, clientId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}


const deleteFavoriteResto = async (clientId, menuRestoId) => {
          try {
                    return query('DELETE FROM clients_resto_favoris WHERE ID_RESTO = ? AND ID_CLIENT = ?', [menuRestoId, clientId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const getRestaurants = async (clientId, offset = 0, limit = 10) => {
          try {
                    var binds = [clientId]
                    var sqlQuery = "SELECT resto.*, resto.TEL_RESPONSABLE, resto.MAP_URL, rf.ID_RESTO_FAVORI "
                    sqlQuery += "FROM clients_resto_favoris rf "
                    sqlQuery += "LEFT JOIN restaurants resto ON resto.ID_RESTAURANT = rf.ID_RESTO "
                    sqlQuery += "ORDER BY DATE_INSERTION DESC "
                    sqlQuery += `LIMIT ${offset}, ${limit}`
                    return await query(sqlQuery, binds)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const getPromotions = async () => {
          try {
                    var sqlQuery = "SELECT * FROM publicites WHERE IS_ACTIF = 1 "
                    return query(sqlQuery);
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getMenus,
          getRestaurants,
          createFavoriteMenu,
          createFavoriteResto,
          deleteFavoriteMenu,
          deleteFavoriteResto,
          getPromotions
}