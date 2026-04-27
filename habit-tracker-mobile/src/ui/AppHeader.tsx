import React from "react";
import { StyleSheet, View, ViewStyle } from "react-native";
import { AppButton } from "./AppButton";
import { AppCard } from "./AppCard";
import { AppText } from "./AppText";

type HeaderAction = {
  title: string;
  onPress: () => void;
};

type Props = {
  title: string;
  subtitle?: string;
  rightActions?: HeaderAction[];
  style?: ViewStyle;
  points?: number;
  level?: number;
};

export function AppHeader({
  title,
  subtitle,
  rightActions = [],
  style,
  points,
  level,
}: Props) {
  const showRanking = points !== undefined && level !== undefined;

  return (
    <View style={[styles.wrap, style]}>
      <AppCard>
        <View style={styles.row}>
          {/* LEFT */}
          <View style={styles.left}>
            <AppText variant="h2" numberOfLines={1}>
              {title}
            </AppText>

            {!!subtitle && (
              <AppText variant="small" style={styles.subtitle} numberOfLines={1}>
                {subtitle}
              </AppText>
            )}
          </View>

          {/* RIGHT */}
          <View style={styles.right}>
            {showRanking && (
              <View style={styles.rankingChip}>
                <AppText variant="small" style={styles.rankingText}>
                  ⭐ {points}
                </AppText>
                <View style={styles.dot} />
                <AppText variant="small" style={styles.rankingText}>
                  🏅 Lv {level}
                </AppText>
              </View>
            )}

            <View style={styles.actionsRow}>
              {rightActions.map((a) => (
                <AppButton
                  key={a.title}
                  title={a.title}
                  onPress={a.onPress}
                  size="sm"
                />
              ))}
            </View>
          </View>
        </View>
      </AppCard>
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: {
    marginBottom: 12,
  },
  row: {
    flexDirection: "row",
    alignItems: "flex-start",
    gap: 12,
  },
  left: {
    flex: 1,
    minWidth: 0, // 🔥 pozwala ucinać tekst zamiast łamać dziwnie
  },
  subtitle: {
    marginTop: 4,
    opacity: 0.85,
  },
  right: {
    alignItems: "flex-end",
    gap: 8,
    maxWidth: "52%", // 🔥 prawa strona nie kradnie całej szerokości
  },

  // Ranking chip
  rankingChip: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 6,
    paddingHorizontal: 10,
    borderRadius: 999,
    backgroundColor: "rgba(255,255,255,0.12)",
  },
  rankingText: {
    opacity: 0.95,
  },
  dot: {
    width: 4,
    height: 4,
    borderRadius: 4,
    marginHorizontal: 8,
    backgroundColor: "rgba(255,255,255,0.45)",
  },

  // Actions (buttons) row
  actionsRow: {
    flexDirection: "row",
    gap: 8,
    flexWrap: "wrap",
    justifyContent: "flex-end",
  },
});