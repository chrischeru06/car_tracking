const express = require('express')
const commandesController = require('../controller/commandes.controller')

const commandesRouter = express.Router()

commandesRouter.get('/', commandesController.getAll)
commandesRouter.get('/:commandeId', commandesController.getCommandeDetail)
commandesRouter.post('/', commandesController.createCommandes)

module.exports = commandesRouter