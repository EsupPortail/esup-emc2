-- Insertion de la catégorie associée à la gestion des utilisateurs
INSERT INTO public.categorie_privilege (id, code, libelle, ordre) VALUES (1, 'droit', 'Gestion des rôles et privilèges', 2000);
INSERT INTO public.categorie_privilege (id, code, libelle, ordre) VALUES (4, 'utilisateur', 'Gestion des utilisateurs', 1600);

-- Insertion des privilèges de base
INSERT INTO public.privilege (id, categorie_id, code, libelle, ordre) VALUES (1, 1, 'role-visualisation', 'Visualisation des rôles', 1);
INSERT INTO public.privilege (id, categorie_id, code, libelle, ordre) VALUES (2, 1, 'role-edition', 'Édition des rôles', 2);
INSERT INTO public.privilege (id, categorie_id, code, libelle, ordre) VALUES (3, 1, 'privilege-visualisation', 'Visualisation des privilèges', 3);
INSERT INTO public.privilege (id, categorie_id, code, libelle, ordre) VALUES (4, 1, 'privilege-edition', 'Édition des privilèges', 4);

-- Insertion des privilèges associés à la gestion des utilisateurs
INSERT INTO public.privilege (id, categorie_id, code, libelle, ordre) VALUES (19, 4, 'ajouter-utilisateur', 'Ajouter un utilisateur', 102);
INSERT INTO public.privilege (id, categorie_id, code, libelle, ordre) VALUES (18, 4, 'afficher-utilisateur', 'Visualiser les informations utilisateurs', 101);
INSERT INTO public.privilege (id, categorie_id, code, libelle, ordre) VALUES (21, 4, 'modifier-role', 'Modifier les rôles d''une utilisateur', 104);
INSERT INTO public.privilege (id, categorie_id, code, libelle, ordre) VALUES (20, 4, 'changer-status', 'Changer le status d''un utilisateur', 103);
