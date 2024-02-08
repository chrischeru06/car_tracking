const commandesModel = require('../model/commandes.model')

const getAll = async (req, res) => {
          try {
                    const { q, offset, limit } = req.query
                    const allCommandes = await commandesModel.getAll(req.clientId, q, offset, limit)
                    res.status(200).json(allCommandes)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

const getCommandeDetail = async (req, res) => {
          try {
                    const { q, offset, limit } = req.query
                    const { commandeId } = req.params
                    const allCommandes = await commandesModel.getCommandeDetail(commandeId, q, offset, limit)
                    const commandes = allCommandes.map(commande => {
                              const { IMAGE_1, ...other } = commande
                              var IMAGES = []
                              if(IMAGE_1) IMAGES.push(IMAGE_1)
                              return {
                                        ...other,
                                        IMAGES
                              }
                    })
                    res.status(200).json(commandes)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

const createCommandes = async (req, res) => {
          const {
                    PROVINCE_ID_LIVRAISON,
                    COMMUNE_ID_LIVRAISON,
                    POSITION_STATUS,
                    ZONE_ID_LIVRAISON,
                    COLLINE_ID_LIVRAISON,
                    LONGITUDE_LIVRAISON,
                    LATITUDE_LIVRAISON,
                    ADRESSE_LIVRAISON,
                    LATITUDE_CLIENT,
                    LONGITUDE_CLIENT,
                    NOM_LIVRAISON,
                    PRENOM_LIVRAISON,
                    NUMERO_LIVRAISON,
                    commandes
          } = req.body
          var MONTANT_COMMANDE = 0
          commandes.forEach(commande => MONTANT_COMMANDE += (commande.PRIX_UNITAIRE * commande.QUANTITE))
          const MONTANT_TRANSPORT = 0
          const MONTANT_SERVICE = 0
          MONTANT_TOTAL = MONTANT_COMMANDE + MONTANT_TRANSPORT + MONTANT_SERVICE
          const data = [req.clientId, NOM_LIVRAISON, PRENOM_LIVRAISON, NUMERO_LIVRAISON, POSITION_STATUS, PROVINCE_ID_LIVRAISON, COMMUNE_ID_LIVRAISON, ADRESSE_LIVRAISON, LONGITUDE_LIVRAISON, LATITUDE_LIVRAISON, LATITUDE_CLIENT, LONGITUDE_CLIENT, MONTANT_COMMANDE, MONTANT_TRANSPORT, MONTANT_SERVICE, MONTANT_TOTAL]
          try {
                    const lastCommande = await commandesModel.create(data)
                    commandes.forEach(async (commande) => {
                              const { ID_MENU_RESTO, PRIX_UNITAIRE, QUANTITE } = commande
                              const ID_COMMANDE = lastCommande.insertId
                              const PRIX_TOTAL = PRIX_UNITAIRE * QUANTITE
                              const detail = [ID_COMMANDE, ID_MENU_RESTO, PRIX_UNITAIRE, QUANTITE, PRIX_TOTAL]
                              const commandeDetail = await commandesModel.createCommandeDetail(detail)
                    })
                    res.status(201).json({...req.body, ID_COMMANDE: lastCommande.insertId})
          } catch (error) {
                    console.log(data)
          }
}

module.exports = {
          getAll,
          getCommandeDetail,
          createCommandes
}