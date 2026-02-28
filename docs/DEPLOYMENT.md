# Zenith Moodle Theme — Deployment Guide

## Architecture

```
Internet
   │
   ▼
Coolify Traefik Proxy (ports 80/443)
   │
   ├── lms.endlessmaker.com → zenith-moodle:8081
   │
   └── (Other services: api.endlessmaker.com, etc.)

Internal Network (zenith-internal):
   zenith-moodle ←→ zenith-mariadb:3306
```

## Prerequisites

- Docker & Docker Compose installed
- Coolify running with `coolify` external network
- Domain DNS pointing to server: `lms.endlessmaker.com`
- Access to Coolify proxy container for Traefik config

## Quick Start (Development)

```bash
cd docker

# 1. Configure environment
cp .env.example .env
# Edit .env with your credentials

# 2. Start services
docker compose up -d

# 3. Access Moodle
open http://localhost:8081
# Complete Moodle installation wizard
```

## Production Deployment (Coolify)

### Step 1: Environment Setup

```bash
cd docker
cp .env.example .env
# Edit with production credentials (strong passwords!)
```

### Step 2: Deploy Containers

```bash
docker compose -f docker-compose.coolify.yml up -d
```

### Step 3: Configure Traefik Routing

Add to Coolify's Traefik dynamic config at `/traefik/dynamic/zenith.yaml`:

```yaml
http:
  routers:
    zenith-moodle:
      rule: "Host(`lms.endlessmaker.com`)"
      service: zenith-moodle
      entryPoints:
        - https
      tls:
        certResolver: letsencrypt

  services:
    zenith-moodle:
      loadBalancer:
        servers:
          - url: "http://zenith-moodle:8081"
```

Copy into the coolify-proxy container:
```bash
docker cp zenith.yaml coolify-proxy:/traefik/dynamic/zenith.yaml
```

### Step 4: Complete Moodle Install

1. Navigate to `https://lms.endlessmaker.com`
2. Follow the Moodle installation wizard
3. Set admin credentials
4. Install the Zenith theme from Site Administration → Appearance → Themes

### Step 5: Verify Health

```bash
# Check containers
docker compose -f docker-compose.coolify.yml ps

# Check health
curl -s https://lms.endlessmaker.com/ | head -5

# Check logs
docker logs zenith-moodle --tail 50
docker logs zenith-mariadb --tail 50
```

## Container Details

| Service | Image | Port | Container Name |
|---------|-------|------|----------------|
| Moodle | bitnami/moodle:4.5 | 8081 | zenith-moodle |
| MariaDB | mariadb:11 | 3306 | zenith-mariadb |

## Volumes

| Volume | Purpose |
|--------|---------|
| zenith_moodle_data | Moodle data directory (uploads, cache) |
| zenith_mariadb_data | Database persistence |
| ./theme/ | Theme source (bind mount for development) |

## Port Allocation

| Port | Service | Status |
|------|---------|--------|
| 80 | Coolify Traefik (HTTP) | Used by Coolify |
| 443 | Coolify Traefik (HTTPS) | Used by Coolify |
| 8080 | Traefik Dashboard | Used by Coolify |
| 8081 | **Zenith Moodle** | **Our project** |
| 8090 | EndlessMaker Web | Used by other project |
| 3306 | **Zenith MariaDB** | **Our project** |

## Updating the Theme

```bash
# After code changes:
# 1. Copy theme files into container
docker cp theme/zenith zenith-moodle:/bitnami/moodle/theme/zenith

# 2. Purge Moodle caches
docker exec zenith-moodle php /bitnami/moodle/admin/cli/purge_caches.php

# Or with bind mount (development):
# Theme changes are instant, just purge cache
docker exec zenith-moodle php /bitnami/moodle/admin/cli/purge_caches.php
```

## Database Operations

```bash
# Backup
docker exec zenith-mariadb mysqldump -u moodle -p'<password>' moodle > backup.sql

# Restore
docker exec -i zenith-mariadb mysql -u moodle -p'<password>' moodle < backup.sql

# Shell access
docker exec -it zenith-mariadb mysql -u moodle -p'<password>' moodle
```

## Troubleshooting

### Theme not appearing
```bash
docker exec zenith-moodle php /bitnami/moodle/admin/cli/purge_caches.php
```

### Permission issues
```bash
docker exec zenith-moodle chown -R daemon:daemon /bitnami/moodle/theme/zenith
```

### Container won't start
```bash
docker logs zenith-moodle 2>&1 | tail -30
docker logs zenith-mariadb 2>&1 | tail -30
```

### Reset everything
```bash
docker compose -f docker-compose.coolify.yml down -v
docker compose -f docker-compose.coolify.yml up -d
# Warning: This deletes all data!
```
