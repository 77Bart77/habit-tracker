import React from "react";
import { StyleSheet, View } from "react-native";
import { useAuth } from "../../src/auth/AuthContext";
import { AppBackground } from "../../src/ui/AppBackground";
import { AppButton } from "../../src/ui/AppButton";
import { AppCard } from "../../src/ui/AppCard";
import { AppHeader } from "../../src/ui/AppHeader";
import { AppText } from "../../src/ui/AppText";

export default function ProfileScreen() {
  const { user, signOut, totalPoints, level } = useAuth();

  return (
    <AppBackground>
      <AppHeader
        title="Profil"
        subtitle={user?.email}
        points={totalPoints}
        level={level}
      />

      {/* Dane użytkownika */}
      <AppCard>
        <AppText variant="body" style={styles.name}>
          {user?.name}
        </AppText>

        <AppText style={styles.email}>
          {user?.email}
        </AppText>
      </AppCard>

      {/* Statystyki */}
      <AppCard>
        <AppText variant="body" style={styles.sectionTitle}>
          Twoje statystyki
        </AppText>

        <View style={styles.statsRow}>
          <AppText>⭐ Punkty</AppText>
          <AppText style={styles.bold}>{totalPoints}</AppText>
        </View>

        <View style={styles.statsRow}>
          <AppText>🏅 Level</AppText>
          <AppText style={styles.bold}>{level}</AppText>
        </View>
      </AppCard>

      {/* Logout */}
      <View style={{ marginTop: 16 }}>
        <AppButton title="Wyloguj" onPress={signOut} />
      </View>
    </AppBackground>
  );
}

const styles = StyleSheet.create({
  name: {
    fontWeight: "800",
    fontSize: 18,
  },
  email: {
    marginTop: 6,
    opacity: 0.8,
  },
  sectionTitle: {
    fontWeight: "800",
    marginBottom: 12,
  },
  statsRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 8,
  },
  bold: {
    fontWeight: "700",
  },
});