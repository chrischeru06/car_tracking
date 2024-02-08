const express = require('express')
const clientsController = require('../controller/clients.controller')

const clientsRouter = express.Router()

clientsRouter.post('/', clientsController.createUser)
clientsRouter.post('/login', clientsController.login)

module.exports = clientsRouter