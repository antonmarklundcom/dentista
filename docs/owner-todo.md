# Pendientes del dueño

- Configurar `config.php` en Hostinger (clave VenderCRM, WhatsApp, hash de admin, `email_to`) y probar un lead.
- Confirmar que `leads/` es escribible y que `/leads/` responde 403 en producción.
- Enviar `sitemap.xml` a Search Console y Bing Webmaster; poner el código en `gsc_verification`.
- Completar en `content/site.php` los datos reales cuando existan (`email`, `instagram`, `facebook`): el schema los toma solo.
- Schema `Dentist`/`MedicalBusiness`/`LocalBusiness` no se agregó: hace falta dirección, horarios y matrícula reales. No se inventaron.
- Imágenes: no faltan las 26 del manifiesto (todas presentes). Pendientes opcionales, no generadas:
  - una imagen de portada propia por cada guía de salud dental (hoy `image => null`), incluidas las nuevas `dentista-cerca-de-mi` y `odontologia-familiar`;
  - fotos reales de los consultorios/profesionales cuando los haya (reemplazan las ilustrativas).
