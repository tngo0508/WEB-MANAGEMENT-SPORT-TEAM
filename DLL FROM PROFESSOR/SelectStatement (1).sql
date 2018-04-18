USE SPORTS_MANAGEMENT;  

  
SELECT 
  G.START_DAY, G.END_DAY, G.SCORE, 
  T1.TEAM_NAME, T1.WIN, T1.LOSS, 
  T2.TEAM_NAME, T2.WIN, T2.LOSS
FROM
  GAMES as G,
  TEAMS as T1,
  TEAMS as T2,
  PLAY  as P
WHERE
  G.GAME_ID   = P.PGAME_ID AND 
  (P.PTEAM_ID = T1.TEAM_ID OR P.PTEAM_ID = T2.TEAM_ID) AND
  T1.TEAM_ID != T2.TEAM_ID
GROUP BY
  G.GAME_ID
ORDER BY
  G.START_DAY,
  T1.TEAM_NAME;

