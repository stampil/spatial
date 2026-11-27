# SUIVI

## À faire

1. Textures de planètes : « fait générer d'autres prompt de texture planete, c une tache infini c moi qui metrai pause », « donne les 1 par 1 », puis « k récupere a chaque "k" les texture et integre les direct ».
   Compris : tâche continue.
   a. Un prompt prêt à coller est donné toutes les minutes (planning), à partir de tools/prompts-images.md. Dernier prompt donné : planete-67. Ces ajouts ne sont plus commités à part : ils partent avec le commit d'intégration suivant.
   b. À chaque « k » de l'utilisateur : je prends dans ~/Téléchargements les images plus récentes que la dernière intégrée, je regarde chacune (rendu 2:1, planète plate, bon type de monde), je la copie dans img/texture/<n>.jpg avec le numéro suivant, je la raccorde (tools/raccorder-planete.py, 1440 × 720, raccord vérifié), j'ajuste Planete::TEXTURES, puis commit, push et publication (autorisation permanente).
   Textures intégrées : 51 = planete-51 (soufre, Gemini_Generated_Image_xknaojxknaojxkna.jpeg), 52 = planete-52 (champignons bioluminescents, Gemini_Generated_Image_6ermab6ermab6erm.jpeg), 53 = planete-53 (colonnes de basalte, Gemini_Generated_Image_enp258enp258enp2.jpeg). 54 = planete-55 (delta marécageux, Gemini_Generated_Image_suoxf2suoxf2suox.jpeg). Refusée : l'image de 16:38 (planete-54, marbre) recopiait la vraie Terre ; prompt 54 à régénérer avec la clause « continents inventés ». 55 = planete-59 (goudron, Gemini_Generated_Image_h2bx9ih2bx9ih2bx.jpeg), 56 = planete-60 (désert de verre, Gemini_Generated_Image_11iiyu11iiyu11ii.jpeg). 57 = planete-62 (océan de varech, Gemini_Generated_Image_wyses3wyses3wyse.jpeg). 58 = planete-64 (croûte de glace brisée, Gemini_Generated_Image_4cwdg84cwdg84cwd.jpeg). Refusée : l'image de 16:42:15 (planete-63, sources chaudes) reproduisait la disposition de la vraie Terre (forme du Groenland en haut) ; prompt 63 à régénérer avec la clause « continents inventés ». À régénérer : planete-54 et planete-63. 59 = planete-65 (oasis arides, Gemini_Generated_Image_qzae5zqzae5zqzae.jpeg), 60 = planete-66 (croûte de lave, Gemini_Generated_Image_86wbsd86wbsd86wb.jpeg). Dernière image intégrée ou examinée : celle du 27/11 à 16:43:32.

## Questions en suspens

Aucune.
