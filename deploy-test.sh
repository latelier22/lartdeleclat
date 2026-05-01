#!/bin/bash
set -e

SSH_USER="debian"
SSH_HOST="vps.latelier22.fr"
REMOTE_DIR="/home/debian/lartdeleclat"

echo "📦 Build Astro..."
npm run build

echo "📁 Création du dossier distant..."
ssh ${SSH_USER}@${SSH_HOST} "mkdir -p ${REMOTE_DIR}"

echo "🚀 Envoi vers ${REMOTE_DIR}..."
rsync -avz --delete dist/ ${SSH_USER}@${SSH_HOST}:${REMOTE_DIR}/

echo "✅ Déploiement terminé : ${REMOTE_DIR}"
